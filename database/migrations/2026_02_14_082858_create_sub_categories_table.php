<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('sub_category_name', 100);
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->integer('sla_minutes')->comment('SLA in minutes: 30, 120, 1440, etc');
            $table->unsignedBigInteger('default_specialist_id')
                  ->nullable()
                  ->comment('Default helpdesk user to auto-assign');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
                  
            $table->foreign('default_specialist_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Indexes
            $table->index(['category_id', 'is_active']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};