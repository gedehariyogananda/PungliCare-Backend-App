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
        $result = $laporans->map(function ($item) {
            return [
                'id' => $item->id,
                'id_laporan' => $item->laporan->id,
                'judul_laporan' => $item->laporan->judul_laporan,
                'deskripsi_laporan' => $item->laporan->deskripsi_laporan,
                'alamat_laporan' => $item->laporan->alamat_laporan,
                'status_laporan' => $item->laporan->status_laporan,
                'bukti_laporan' => $item->laporan->BuktiLaporan ? $item->laporan->BuktiLaporan[0]->bukti_laporan : null,
                'pendukung' => $item->laporan->VoteLaporan ? $item->laporan->VoteLaporan->count() : 0,
            ];
        });;
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
            ], 400);
        }

        if ($request->confirm_password !== $request->new_password){
            return response()->json([
                'status' => "error",
                'message'=> "Confirm password dengan password baru harus sama"
            ], 400);
        }

        if(!Hash::check($request->old_password, auth()->user()->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'Password lama tidak sesuai'
            ], 400);
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
        ], 500);
       }
    }


}
