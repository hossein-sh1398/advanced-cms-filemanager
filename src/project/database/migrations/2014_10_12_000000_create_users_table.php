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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->string('password');
            $table->boolean('two_step_status')->default(false);
            $table->smallInteger('two_step_type')->nullable();
            $table->string('sms_code')->default(rand(1000, 9999));
            $table->integer('dark_mode')->default(0);
            $table->string('country')->nullable();
            $table->ipAddress('last_ip')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('account_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('google2fa_secret')->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
