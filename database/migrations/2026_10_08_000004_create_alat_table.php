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
        Schema::create('alat', function (Blueprint $table) {
            $table->bigIncrements('id_alat');
            $table->unsignedBigInteger('id_kategori');
            $table->string('kode_alat', 30)->unique();
            $table->string('nama_alat', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_sewa', 12, 2);
            $table->integer('stok_total');
            $table->integer('stok_tersedia');
            $table->integer('stok_dipinjam')->default(0);
            $table->integer('stok_rusak')->default(0);
            $table->decimal('nilai_penggantian', 12, 2);
            $table->string('foto', 255)->nullable();
            $table->enum('status', ['tersedia', 'habis', 'maintenance', 'nonaktif'])->default('tersedia');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Indexing data
            $table->index('kode_alat');
            $table->index('nama_alat');
            $table->index('id_kategori');
            $table->index('status');
            $table->index(['id_kategori', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
