<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function BuktiLaporan()
    {
        return $this->hasMany(BuktiLaporan::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function ReportLaporan()
    {
        return $this->hasMany(ReportLaporan::class);
    }

    public function NotifUser()
    {
        return $this->hasMany(NotifUser::class);
    }

    public function VoteLaporan()
    {
        return $this->hasMany(VoteLaporan::class);
    }

    public function CommentLaporan()
    {
        return $this->hasMany(CommentLaporan::class);
    }

}
