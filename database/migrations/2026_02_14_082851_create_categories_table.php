<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable()->comment('Icon class or emoji');
            $table->integer('order_index')->default(0)->comment('For sorting display');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};