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
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("created_by");
            $table->dateTime("delivered_at")->nullable();
            $table->unsignedBigInteger("driver_id");
            // $table->unsignedBigInteger("client_id");
            $table->decimal("order_cost",10,2)->default(0);
            $table->decimal("delivery_cost",10,2)->default(0);
            $table->string('client_name')->default('');
            $table->string('client_phone')->default('');
            $table->string('client_address')->default('');
            $table->string('client_latitude')->default('');
            $table->string('client_longitude')->default('');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('driver_id')->references('id')->on('drivers');
            // $table->foreign('client_id')->references('id')->on('clients');
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
