<?php

namespace App\Repositories;

use App\Constants\ResponseCodes;

use Hash;

use Illuminate\Support\Facades\Mail;

use App\Mail\ForgotPassword;



/**
 * Class RegisterRepository
 * @package App\Repositories
 * @version June 26, 2023, 2:31 am UTC
 */

class RegisterRepository
{

    /** @var  ForgotPasswordRepository */
    private $forgotPasswordRepository;

    /** @var  UserRepository */
    private $userRepository;


    public function __construct(UserRepository $userRepository,ForgotPasswordRepository $forgotPasswordRepository)
    {
        $this->userRepository           =  $userRepository;

        $this->forgotPasswordRepository = $forgotPasswordRepository;
    }

    /** signup of a managerRegister */
    public function forgotPasswordEmail($request)
    {
        $user = $this->userRepository->wherefirst(['email' => $request->email]);

        if($user)
        {
            $this->forgotPasswordRepository->Wheredelete(['user_id' =>$user->id]);

            $_forgot_password = $this->forgotPasswordRepository->create([

                'user_id' => $user->id,

                'code' => sixdigitOTP(),

                'link_sent_date_time' => date('Y-m-d')
            ]);


            $_attribute = array(

                'name' => $user->name,

                'link' => env('APP_URL').'/reset-verification-code?email='.$user->email,

                'code' => $_forgot_password->code
            );

            // Send email to user
            Mail::to($user->email)->send(new ForgotPassword($_attribute));

            return array('status'=> true,'statusCode' => ResponseCodes::CREATED,'data' => $user,'message' => 'Email sent successfully to your email address');
        }
        else
        {
            return array('status'=> false,'statusCode' => ResponseCodes::BAD_REQUEST,'data' => '','message' => 'user is not register in our system');
        }
    }

    public function forgotPaaswordVerifyCode($request)
    {
        $user = $this->userRepository->wherefirst(['email' => $request->email]);
        
        if(!$user)
        {
            return array('status'=> false,'statusCode' => ResponseCodes::BAD_REQUEST,'data' => $request->email,'message' => 'User is not register in our system');
        }
        
        $_forgot_password = $this->forgotPasswordRepository->wherefirst(['user_id' => $user->id,'code' => implode('',$request->code),'is_used' => 0]);
       
        if(!$_forgot_password)
        {
            return array('status'=> false,'statusCode' => ResponseCodes::BAD_REQUEST,'data' => $request->email,'message' => 'Incorrect Reset Code');
        }

        return array('status'=> true,'statusCode' => ResponseCodes::SUCCESS,'data' => $request->email,'message' => 'Code is verified successfully!');
        
    }

    public function forgotPaaswordChange($request)
    {
        $user = $this->userRepository->wherefirst(['email' => $request->email]);
        
        if(!$user)
        {
            return array('status'=> false,'statusCode' => ResponseCodes::BAD_REQUEST,'data' => $request->email,'message' => 'User is not register in our system');
        }
        
        $_password_change = $this->userRepository->whereUpdate(['id' => $user->id],['password' => Hash::make($request->password)]);
       
        if(!$_password_change)
        {
            return array('status'=> false,'statusCode' => ResponseCodes::BAD_REQUEST,'data' => $request->email,'message' => 'Your new password has been successfully created!');
        }

       $this->forgotPasswordRepository->whereUpdate(['user_id' => $user->id],['is_used' => 1]);

       $this->forgotPasswordRepository->Wheredelete(['user_id' => $user->id],['is_used' => 1]);

        return array('status'=> true,'statusCode' => ResponseCodes::SUCCESS,'data' => '','message' => 'Password is changed successfully!');

    }

}