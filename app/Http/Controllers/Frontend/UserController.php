<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\RegisterRepository;

use App\Http\Requests\PasswordChange;

class UserController extends Controller
{

    /** @var  RegisterRepository */
    private $registerRepository;

    public function __construct(RegisterRepository $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    public function sendEmail(Request $request)
    {

        $this->validate($request, [

            'email' => 'required|email|exists:users,email',

        ]);

        try {

            $_forgot_password = $this->registerRepository->forgotPasswordEmail($request);

            if ($_forgot_password['status']) {

                return redirect('password/reset')->with(['success' => $_forgot_password['message'], 'title' => 'Forgot Password Request Successfully!']);
            } else {
                return redirect('password/reset')->with('error', $_forgot_password['message']);
            }
        } catch (\Exception $e) {
            return redirect('password/reset')->with('error', $e->getMessage());
        }
    }

    public function resetVerifyCode(Request $request)
    {
        return view('auth.passwords.reset-verification-code');
    }

    public function verifyCode(Request $request)
    {
        try {

            $_verify_code = $this->registerRepository->forgotPaaswordVerifyCode($request);

            if ($_verify_code['status']) {
                return redirect('confirm-password?email=' . $request->email)->with(['success' => $_verify_code['message'], 'title' => 'Verfication Code!', 'status' => 'success']);
            } else {
                return redirect('reset-verification-code?email=' . $request->email)->with(['error' => $_verify_code['message'], 'title' => '', 'status' => 'error']);
            }
        } catch (\Exception $e) {

            return redirect('reset-verification-code?email=' . $request->email)->with('error', $e->getMessage());
        }
    }

    public function confirmPassword(Request $request)
    {
        return view('auth.passwords.confirm');
    }


    public function passwordChange(Request $request)
    {
        $this->validate(
            $request,
            [

                'password' => 'required|string|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',

                'confirm_password' => 'required|same:password'
            ],
            [
                'password.regex' => 'The password format is invalid.',
                'password.regex' => 'The password must contain at least one lowercase letter.',
                'password.regex' => 'The password must contain at least one uppercase letter.',
                'password.regex' => 'The password must contain at least one digit.',
                'password.regex' => 'The password must contain a special character.',
            ]
        );


        try {

            $_verify_code = $this->registerRepository->forgotPaaswordChange($request);

            if ($_verify_code['status']) {
                return redirect('/')->with(['success' => $_verify_code['message'], 'title' => 'You have created a new password', 'status' => 'success']);
            } else {
                return redirect('confirm-password?email=' . $request->email)->with('error', $_verify_code['message']);
            }
        } catch (\Exception $e) {

            return redirect('confirm-password?email=' . $request->email)->with('error', $e->getMessage());
        }
    }
}
