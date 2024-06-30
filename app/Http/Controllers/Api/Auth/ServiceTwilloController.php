<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceTwilloController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'nomor_telepon' => 'required|string|unique:users',
            'password' => 'required|string',
            'password_konfirmasi' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $formattedPhone = $this->otpService->formatPhoneNumber($request->nomor_telepon);
            $validOtp = $this->otpService->generateOtp();

            // service twillo forbiden
            // $this->otpService->sendOtp($formattedPhone, $validOtp);

            $temporaryUser = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'nomor_telepon' => $formattedPhone,
                'password' => bcrypt($request->password),
                'kode_otp' => $validOtp,
            ]);

            $token = Auth::guard('api')->login($temporaryUser);

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dibuat, cek nomor telpon untuk verifikasi kode OTP nya ',
                'data' => [
                    'nomor_telepon' => $temporaryUser->nomor_telepon,
                    'is_verifikasi' => false,
                    'token' => $token,
                    'kode_otp' => $validOtp
                ],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifikasiOtpSms(Request $request)
    {
        $authUserInit = auth()->user();
        $validator = Validator::make($request->all(), [
            'kode_otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $user = User::where('id', $authUserInit->id)->first();

            if ($user->kode_otp == $request->kode_otp) {
                $user->phone_number_verified_at = now();
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Nomor telepon berhasil diverifikasi',
                    'data' => [
                        'nomor_telepon' => $user->nomor_telepon,
                        'phone_number_verified_at' => $user->phone_number_verified_at,
                        'is_verifikasi' => true,
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak valid',
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$token = Auth::guard('api')->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        try {
            $userValid = User::where('email', $request->email)->first();

            if ($userValid->phone_number_verified_at == null) {
                $validOtp = $this->otpService->generateOtp();

                // service twillo forbidden
                // $this->otpService->sendOtp($userValid->nomor_telepon, $validOtp);

                $userValid->update(['kode_otp' => $validOtp]);

                return response()->json([
                    'success' => true,
                    'message' => 'Akun belum diverifikasi, cek nomor telpon untuk verifikasi kode OTP nya ',
                    'data' => [
                        'nomor_telepon' => $userValid->nomor_telepon,
                        'is_verifikasi' => false,
                        'token' => $token,
                        'kode_otp' => $validOtp,
                    ],
                ], 201);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'is_verifikasi' => true,
                    'token' => $token,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resendOtpSms()
    {
        $user = auth()->user();

        try {
            $validOtp = $this->otpService->generateOtp();
            // $this->otpService->sendOtp($user->nomor_telepon, $validOtp);

            $updateOtp = User::where('id', $user->id)->update(['kode_otp' => $validOtp]);

            if ($updateOtp) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP berhasil dikirim ulang',
                    'data' => [
                        'nomor_telepon' => $user->nomor_telepon,
                        'is_verifikasi' => false,
                        'kode_otp' => $validOtp,
                    ],
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }
}
