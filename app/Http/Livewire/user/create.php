<?php

namespace App\Http\Livewire\user;

use Livewire\Component;

use App\Repositories\RoleRepository;

use App\Repositories\UserRepository;

use App\Repositories\UserprofileRepository;

use App\Constants\RoleConstant;

use Illuminate\Support\Facades\Mail;

use App\Mail\SendPassword;

use Illuminate\Support\Facades\Hash;

use App\Http\Trait\ToastTrait;

class Create extends Component
{
    use ToastTrait;

    /** @var  RoleRepository */
    protected $roleRepository;

    /** @var  UserRepository */
    protected $userRepository;

    /** @var  UserprofileRepository */
    protected $userprofileRepository;

    public $load = false;

    public $first_name, $last_name, $email, $role, $roles = [], $message, $userExists;

    protected $listeners = ['updateRole'];

    public function hydrate()
    {
        $this->setRepository();
    }

    public function setRepository()
    {

        $this->roleRepository           = app()->make(RoleRepository::class);

        $this->userRepository           = app()->make(UserRepository::class);

        $this->userprofileRepository    = app()->make(UserprofileRepository::class);
    }

    public function updateRole($value)
    {
        $this->role   = $value;

        $this->resetErrorBag('role');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields()
    {

        $this->first_name            = '';

        $this->last_name            = '';

        $this->email                = '';

        $this->role                 = '';
    }

    /** create project user */
    public function store()
    {
        $validatedData = $this->validate([
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:100',
            'email'      => 'required|email',
            'role'       => 'required'
        ]);

        $this->load = true;

        // Check if a user with this email already exists
        $existingUser = $this->userRepository->whereFirst(['email' => $validatedData['email']]);

        if ($existingUser) {
            // If user exists, update the role
            $role = $this->roleRepository->whereFirst(['id' => $this->role]);
            if ($role) {
                $existingUser->roles()->attach($role);
            }
            $user = $existingUser;
        } else {
            // If user does not exist, create a new user
            $_user_attribute = [
                'name'  => $this->first_name . ' ' . $this->last_name,
                'email' => $validatedData['email']
            ];

            $user = $this->userRepository->create($_user_attribute);

            $role = $this->roleRepository->whereFirst(['id' => $this->role]);
            if ($role) {
                $user->roles()->attach($role);
            }
            $_user_profile_attribute = [
                'first_name' => $this->first_name,
                'last_name'  => $this->last_name,
                'user_id'    => $user->id
            ];

            $this->userprofileRepository->create($_user_profile_attribute);
        }
        $_generate_password = $this->generateStrongPassword();

        $_attribute = [
            'name'     => $user->name,
            'link'     => env('APP_URL') . '/login',
            'password' => $_generate_password
        ];

        $this->userRepository->whereUpdate(['id' => $user->id], ['password' => Hash::make($_generate_password)]);
        // Send email to user
        $mail = Mail::to($user->email)->send(new SendPassword($_attribute));

        if ($mail) {
            $this->load = false;
        }

        $this->toastMessage('Success!', 'User Created Successfully!', '/user', 'success');
    }


    public function updated($name)
    {
        // Validate only the updated field
        $this->validateOnly($name, [
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:100',
            'email'      => 'required|email',
            'role'       => 'required'
        ]);

        if ($name == 'email') {
            // Check if a user with this email already exists
            $existingUser = $this->userRepository->whereFirst(['email' => $this->email]);
            if ($existingUser) {
                // If user exists, fill in the details and disable inputs
                $this->first_name = $existingUser->userprofile->first_name;
                $this->last_name = $existingUser->userprofile->last_name;
                $this->userExists = true;
            } else {
                // If user does not exist, enable inputs
                // $this->first_name = '';
                // $this->last_name = '';
                 $this->userExists = false;
            }
        }
    }



    public function generateStrongPassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        $allChars = $uppercase . $lowercase . $numbers . $specialChars;
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $randomChar = $allChars[rand(0, strlen($allChars) - 1)];
            $password .= $randomChar;
        }

        return $password;
    }


    public function render()
    {
        $this->setRepository();

        $this->roles =  $this->roleRepository->whereNotIn('id', [RoleConstant::DONOR, RoleConstant::EMPLOYEE, RoleConstant::CONSULTANT, RoleConstant::SUBGRANTEE]);

        $this->load = false;

        return view('livewire.user.user-create', ['roles' => $this->roles]);
    }
}
