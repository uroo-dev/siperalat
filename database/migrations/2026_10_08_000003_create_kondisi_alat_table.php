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
        Schema::create('kondisi_alat', function (Blueprint $table) {
            $table->bigIncrements('id_kondisi');
            $table->string('nama_kondisi', 50)->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('dapat_dipinjam')->default(1);
            $table->timestamps();

            // Indexing data
            $table->index('nama_kondisi');
            $table->index('dapat_dipinjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_alat');
    }
};
