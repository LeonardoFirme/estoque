<?php
// database/migrations/2026_03_08_204428_add_payment_fields_to_outputs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            $table->string('payment_method')->default('money')->after('type');
            $table->integer('installments')->default(1)->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'installments']);
        });
    }
};