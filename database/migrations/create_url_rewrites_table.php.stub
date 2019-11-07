<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlRewritesTable extends Migration
{
    public function up()
    {
        Schema::create('url_rewrites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->json('type_attributes')->nullable();
            $table->string('request_path')->index();
            $table->string('target_path');
            $table->smallInteger('redirect_type')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('url_rewrites');
    }
}
