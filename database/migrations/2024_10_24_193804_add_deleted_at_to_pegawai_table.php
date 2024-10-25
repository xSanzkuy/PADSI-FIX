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
    Schema::table('pegawai', function (Blueprint $table) {
        $table->softDeletes(); // Menambahkan kolom deleted_at untuk soft delete
    });
}

public function down()
{
    Schema::table('pegawai', function (Blueprint $table) {
        $table->dropSoftDeletes(); // Menghapus kolom deleted_at jika rollback
    });
}

};
