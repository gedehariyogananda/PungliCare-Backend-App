<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\OtpCode;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $otp = rand(1000, 9999);
        $expiresAt = Carbon::now()->addMinutes(5);

        OtpCode::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        // return response()->json($request->email);

        Mail::to($request->email)->send(new ForgotPassword($otp));

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request){
        // return response()->json($request);
        $validated = Validator::make($request->all(),[
            'email' =>'required|email',
            'otp' => 'required|numeric',
            'new_password' => 'required|string'
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(), 400);
        }
        
        $otp = OtpCode::where('email', $request->email)
                        ->where('otp',$request->otp)
                        ->where('expires_at', '>',Carbon::now())->first();

        // return response()->json($otp);
        if(!$otp){
            return response()->json([
                'status' =>'error',
                'message' => 'invalid otp or expired otp'
            ]);
        }else{
            $user = User::where('email', $request->email);
            $user->password = Hash::make($request->new_password);
            return response()->json([
                'status' =>'success',
                'message' => 'password updated successfully'
            ]); 
        }
    }
}