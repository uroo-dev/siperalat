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
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->bigIncrements('id_detail_pengembalian');
            $table->unsignedBigInteger('id_pengembalian');
            $table->unsignedBigInteger('id_detail_peminjaman');
            $table->integer('jumlah_kembali');
            $table->unsignedBigInteger('id_kondisi');
            $table->text('keterangan_kondisi')->nullable();
            $table->string('foto_kondisi', 255)->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_pengembalian')
                ->references('id_pengembalian')
                ->on('pengembalian')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_detail_peminjaman')
                ->references('id_detail')
                ->on('detail_peminjaman')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_kondisi')
                ->references('id_kondisi')
                ->on('kondisi_alat')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Indexing data
            $table->index('id_pengembalian');
            $table->index('id_detail_peminjaman');
            $table->index('id_kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};
