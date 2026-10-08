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
        Schema::create('denda', function (Blueprint $table) {
            $table->bigIncrements('id_denda');
            $table->unsignedBigInteger('id_pengembalian');
            $table->unsignedBigInteger('id_detail_pengembalian')->nullable();
            $table->unsignedBigInteger('id_jenis_denda');
            $table->integer('jumlah_hari_terlambat')->default(0);
            $table->integer('jumlah_alat')->default(0);
            $table->decimal('tarif', 12, 2);
            $table->decimal('nominal', 12, 2);
            $table->text('alasan');
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas', 'dibebaskan'])->default('belum_lunas');
            $table->unsignedBigInteger('dibebaskan_oleh')->nullable();
            $table->text('alasan_pembebasan')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_pengembalian')
                  ->references('id_pengembalian')
                  ->on('pengembalian')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_detail_pengembalian')
                  ->references('id_detail_pengembalian')
                  ->on('detail_pengembalian')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->foreign('id_jenis_denda')
                  ->references('id_jenis_denda')
                  ->on('jenis_denda')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('dibebaskan_oleh')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            // Indexing data
            $table->index('id_pengembalian');
            $table->index('id_detail_pengembalian');
            $table->index('id_jenis_denda');
            $table->index('status');
            $table->index('dibebaskan_oleh');
            $table->index(['status', 'id_pengembalian']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
