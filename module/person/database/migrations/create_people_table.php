<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('demonstration_name', 100);
            $table->boolean('active')->default(0);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->integer('social_id')->unsigned();
            $table->date('birth_date');
            $table->string('mobile_number', 15)->unique();
            $table->string('mobile_number_description', 100)->unique();
            $table->string('email')->unique();
            $table->string('email_description', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
};
