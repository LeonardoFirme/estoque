<?php
// database/migrations/2026_03_08_214205_add_employee_id_to_outputs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            $table->foreignUuid('employee_id')
                ->nullable()
                ->after('product_id')
                ->constrained('employees')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }
};