<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dealer_branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_name', 100);
            $table->string('branch_code', 20)->unique();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('pic_name', 100)->nullable()->comment('Person in Charge');
            $table->string('pic_email', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('branch_code');
            $table->index('is_active');
        });
        
        // Add foreign key to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('dealer_branch_id')
                  ->references('id')
                  ->on('dealer_branches')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dealer_branch_id']);
        });
        
        Schema::dropIfExists('dealer_branches');
    }
};