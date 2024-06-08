<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('kode_pajak', 10)->nullable(false);
            $table->string('nama_pajak', 100)->nullable(false);
            $table->string('jenis_pajak', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('tarif_pajak', 20)->nullable(false);
            $table->date('tanggal_berlaku')->nullable(false);
            $table->foreign('nama_pajak')->references('id')->on('jenis_pjk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
