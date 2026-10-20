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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->index();
            $table->string('payment_authority')->nullable()->unique();
            $table->string('payment_reference')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['payment_authority']);
            $table->dropUnique(['payment_reference']);
            $table->dropIndex(['payment_status']);
            $table->dropColumn(['payment_status', 'payment_authority', 'payment_reference']);
        });
    }
};
