<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiLaporan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id');
    }
}
