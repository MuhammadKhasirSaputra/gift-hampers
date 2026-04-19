<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('Pending');
            $table->text('shipping_address');
            $table->string('payment_method')->default('Transfer Bank');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};