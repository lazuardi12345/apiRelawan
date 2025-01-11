<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('formulirs', function (Blueprint $table) {
        $table->id();
        $table->string('nama')->nullable();
        $table->enum('jenis_kelamin', ['laki-laki', 'wanita'])->nullable();
        $table->string('tempat')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->string('provinsi')->nullable();
        $table->string('kabupaten')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('kelurahan')->nullable();
        $table->string('email')->nullable();
        $table->string('no_hp')->nullable();
        $table->string('ktp')->nullable(); // Image upload path
        $table->text('motivasi')->nullable();
        $table->text('kontribusi')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulirs'); // Drop the table if it exists
    }
};
