<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('lat');
            $table->string('long');
            $table->text('alamat_laporan')->nullable();
            $table->string('judul_laporan');
            $table->text('deskripsi_laporan');
            $table->enum('status_laporan', ['perlu-dukungan', 'perlu-diatasi', 'sedang-diatasi', 'sudah-teratasi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};
