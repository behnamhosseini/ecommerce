<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->float('quantity', 6)->unsigned()->default(0);
            $table->integer('price')->unsigned()->digits(12)->default(0);
            $table->decimal('amount', 12, 0)->unsigned()->default(0);
            $table->decimal('discount', 12, 0)->unsigned()->default(0);
            $table->decimal('total_after_discount', 12, 0)->unsigned()->default(0);
            $table->decimal('tax', 12, 0)->unsigned()->default(0);
            $table->decimal('total_due', 12, 0)->unsigned()->default(0);
            $table->decimal('total_sum', 12, 0)->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
