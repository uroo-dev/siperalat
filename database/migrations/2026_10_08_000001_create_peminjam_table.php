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
        Schema::create('peminjam', function (Blueprint $table) {
            $table->bigIncrements('id_peminjam');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nama', 100);
            $table->string('no_identitas', 50)->unique();
            $table->string('no_telepon', 20);
            $table->text('alamat');
            $table->string('foto', 255)->nullable();
            $table->enum('status_peminjam', ['aktif', 'diblokir', 'nonaktif'])->default('aktif');
            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexing data
            $table->index('user_id');
            $table->index('no_identitas');
            $table->index('nama');
            $table->index('status_peminjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjam');
    }
};
