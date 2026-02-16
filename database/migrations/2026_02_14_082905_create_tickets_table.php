<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->unsignedBigInteger('dealer_branch_id');
            $table->unsignedBigInteger('created_by')->comment('User ID who created ticket');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('subject', 200);
            $table->text('description');
            
            // Priority & SLA (copied from sub_category)
            $table->enum('priority', ['high', 'medium', 'low']);
            $table->integer('sla_minutes');
            $table->timestamp('sla_deadline')->nullable();
            
            // Assignment
            $table->unsignedBigInteger('assigned_to')->nullable();
            
            // Status
            $table->enum('status', [
                'new', 
                'assigned', 
                'in_progress', 
                'pending', 
                'resolved', 
                'closed', 
                'reopened'
            ])->default('new');
            
            // Timestamps for workflow
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            // Resolution
            $table->text('resolution_note')->nullable();
            $table->integer('actual_minutes_taken')->nullable();
            $table->boolean('sla_met')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('dealer_branch_id')
                  ->references('id')
                  ->on('dealer_branches')
                  ->onDelete('restrict');
                  
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
                  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('restrict');
                  
            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('sub_categories')
                  ->onDelete('restrict');
                  
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Indexes
            $table->index('ticket_number');
            $table->index(['status', 'priority']);
            $table->index('sla_deadline');
            $table->index('dealer_branch_id');
            $table->index('assigned_to');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};