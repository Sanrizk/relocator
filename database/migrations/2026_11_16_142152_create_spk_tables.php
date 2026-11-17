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
        // 1. Update users table with role
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pimpinan'])->default('admin')->after('email');
        });

        // 2. Table kriteria
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria');
            $table->enum('jenis', ['benefit', 'cost']);
            $table->integer('peringkat');
            $table->double('bobot_roc', 8, 4)->default(0);
            $table->timestamps();
        });

        // 3. Table alternatif
        Schema::create('alternatif', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->text('alamat');
            $table->timestamps();
        });

        // 4. Table penilaian
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')->constrained('alternatif')->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
            $table->double('nilai', 10, 2);
            $table->timestamps();
            $table->unique(['alternatif_id', 'kriteria_id']);
        });

        // 5. Table hasil_spk
        Schema::create('hasil_spk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')->constrained('alternatif')->cascadeOnDelete();
            $table->double('nilai_preferensi', 10, 4);
            $table->integer('ranking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_spk');
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('alternatif');
        Schema::dropIfExists('kriteria');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
