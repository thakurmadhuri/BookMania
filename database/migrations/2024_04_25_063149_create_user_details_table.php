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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('pincode');
            $table->boolean('default_address')->nullable();
            $table->string('mobile');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->timestamps();
            $table->softDeletes('deleted_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
