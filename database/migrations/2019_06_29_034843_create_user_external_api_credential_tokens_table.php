<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExternalApiCredentialTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_external_api_credential_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('api_id')->nullable();
            $table->string('refresh_token')->unique();
            $table->softDeletes();
            $table->timestamps();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('api_id')->references('id')->on('user_external_api_credentials')->onDelete('cascade');
            //SETTING THE PRIMARY KEYS
            $table->primary(['api_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_external_api_credential_tokens');
    }
}
