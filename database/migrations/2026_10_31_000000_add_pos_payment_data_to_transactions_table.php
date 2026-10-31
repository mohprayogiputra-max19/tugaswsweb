<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('no_nota')->nullable()->unique();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('pajak', 12, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('paid_amount', 12, 2)->nullable();
            $table->decimal('change_amount', 12, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['no_nota']);
            $table->dropColumn([
                'no_nota',
                'subtotal',
                'pajak',
                'payment_method',
                'paid_amount',
                'change_amount',
            ]);
        });
    }
};
