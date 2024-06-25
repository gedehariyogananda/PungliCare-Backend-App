<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLaporan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function laporan(){
        return $this->belongsTo(Laporan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
