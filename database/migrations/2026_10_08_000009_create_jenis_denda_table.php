<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jenis_denda', function (Blueprint $table) {
            $table->bigIncrements('id_jenis_denda');
            $table->string('nama_denda', 100);
            $table->enum('metode_hitung', ['per_hari', 'tetap', 'persentase', 'nilai_penggantian']);
            $table->decimal('tarif', 12, 2)->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            // Indexing data
            $table->index('nama_denda');
            $table->index('metode_hitung');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_denda');
    }
};
