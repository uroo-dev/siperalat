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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->bigIncrements('id_pengembalian');
            $table->string('kode_pengembalian', 30)->unique();
            $table->unsignedBigInteger('id_peminjaman');
            $table->unsignedBigInteger('diproses_oleh');
            $table->date('tanggal_pengembalian');
            $table->decimal('total_denda', 12, 2)->default(0);
            $table->enum('status_pengembalian', ['sebagian', 'selesai'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('diproses_oleh')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Indexing data
            $table->index('kode_pengembalian');
            $table->index('id_peminjaman');
            $table->index('diproses_oleh');
            $table->index('tanggal_pengembalian');
            $table->index('status_pengembalian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
