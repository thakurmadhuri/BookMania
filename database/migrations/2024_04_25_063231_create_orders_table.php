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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('total_qty');
            $table->string('total_price');
            $table->string('payment_method');
            $table->string('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('pincode');
            $table->string('mobile');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
