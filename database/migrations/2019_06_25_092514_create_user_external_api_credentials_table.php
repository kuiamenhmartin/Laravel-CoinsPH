<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExternalApiCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_external_api_credentials', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
              $table->unsignedBigInteger('user_id');
              $table->string('app_name')->unique();
              $table->string('client_id')->unique();
              $table->string('client_secret')->unique();
              $table->boolean('is_active')->default(true);
              $table->softDeletes();
              $table->timestamps();

          //FOREIGN KEY CONSTRAINTS
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_external_api_credentials');
    }
}
