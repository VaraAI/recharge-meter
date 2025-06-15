<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recharge_histories', function (Blueprint $table) {
            $table->id();
            $table->string('meter_code');
            $table->integer('meter_type');
            $table->decimal('amount', 10, 2);
            $table->string('response_token')->nullable();
            $table->string('response_status')->nullable();
            $table->decimal('balance', 10, 2)->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index('meter_code');
            $table->index('response_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recharge_histories');
    }
}; 