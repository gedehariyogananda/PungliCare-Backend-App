<?php

namespace App\Http\Controllers\Api;

use App\Mail\ForgotPassword;
use App\Models\OtpCode;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Exception;
use App\Models\User;
use App\Models\VoteLaporan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        
    }

    public function getLaporanDukungan(){
       try{
        $userId = auth()->user()->id;
        $laporans = VoteLaporan::with('laporan')->where('user_id', $userId)->get();
        $result = $laporans->map(function($laporan){
            return $laporan->laporan;
        });
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
       }catch(Exception $e){
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
       }
    } 

    public function changePassword(Request $request){
       try{
        $validated = Validator::make($request->all(),[
            'old_password' => 'required:string',
            'new_password' => 'required:string',
            'confirm_password' => 'required:string'
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(), 400);
        }

        if($request->old_password === $request->new_password){
            return response()->json([
                'status' => 'error',
                'message' => 'Password lama dan password baru tidak boleh sama'
            ]);
        }

        if ($request->confirm_password !== $request->new_password){
            return response()->json([
                'status' => "error",
                'message'=> "Confirm password dengan password baru harus sama"
            ]);
        }

        $user = User::findOrFail(auth()->user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil dirubah'
        ]);
       }catch(Exception $e){
        return response()->json([
            'status' =>'error',
            'message'=> $e->getMessage()
        ]);
       }
    }


}
