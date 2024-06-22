<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Laporan()
    {
        return $this->belongsTo(Laporan::class);
    }



}
