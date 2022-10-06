<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_files', function (Blueprint $table) {
            $table->id();
            $table->string("uuid", 36)->unique()->index();
            $table->unsignedBigInteger("user_id")->index();
            $table->unsignedBigInteger("dir_id")->index();
            $table->string("slug", 140);
            $table->string("filename", 112);
            $table->string("disk", 25);
            $table->string("mimeType", 55)->nullable();
            $table->string("clientExt", 5)->nullable();
            $table->double("clientSize")->nullable();
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
        Schema::dropIfExists('user_files');
    }
};
