<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_hp');
            $table->enum('tingkat', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->integer('total_transaksi')->default(0);
            $table->timestamps();
        });
    }    

    public function down()
    {
        Schema::dropIfExists('members');
    }
}
