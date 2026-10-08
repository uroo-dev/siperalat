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
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->bigIncrements('id_detail');
            $table->unsignedBigInteger('id_peminjaman');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah');
            $table->decimal('harga_per_hari', 12, 2);
            $table->integer('lama_pinjam');
            $table->decimal('subtotal', 12, 2);
            $table->integer('jumlah_dikembalikan')->default(0);
            $table->integer('jumlah_rusak')->default(0);
            $table->integer('jumlah_hilang')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_alat')
                ->references('id_alat')
                ->on('alat')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Indexing data
            $table->index('id_peminjaman');
            $table->index('id_alat');
            $table->index(['id_peminjaman', 'id_alat']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
