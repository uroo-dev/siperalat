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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->bigIncrements('id_peminjaman');
            $table->string('kode_transaksi', 30)->unique();
            $table->unsignedBigInteger('id_peminjam');
            $table->dateTime('tanggal_pengajuan');
            $table->date('tanggal_pinjam')->nullable();
            $table->date('tanggal_kembali_rencana');
            $table->integer('lama_pinjam');
            $table->text('keperluan');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total_denda', 12, 2)->default(0);
            $table->decimal('total_tagihan', 12, 2)->default(0);
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'dipinjam',
                'sebagian_dikembalikan',
                'dikembalikan',
                'dibatalkan'
            ])->default('menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->dateTime('disetujui_at')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_peminjam')
                ->references('id_peminjam')
                ->on('peminjam')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('disetujui_oleh')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            // Indexing data
            $table->index('kode_transaksi');
            $table->index('id_peminjam');
            $table->index('status');
            $table->index('tanggal_pengajuan');
            $table->index('tanggal_pinjam');
            $table->index('tanggal_kembali_rencana');
            $table->index('disetujui_oleh');
            $table->index(['status', 'tanggal_pengajuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
