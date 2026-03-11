<?php
// database/migrations/2026_03_08_000002_create_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique();
            $table->string('ean_13', 13)->nullable()->unique();
            $table->string('ncm', 8)->nullable();
            $table->uuid('category_id')->nullable()->index();
            $table->string('image_path')->nullable();
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('operational_cost', 15, 2)->default(0);
            $table->decimal('price', 15, 2);
            $table->decimal('iof_rate', 5, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(5);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};