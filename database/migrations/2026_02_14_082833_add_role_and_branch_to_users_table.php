<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['dealer', 'admin_it', 'helpdesk', 'super_admin'])
                  ->default('dealer')
                  ->after('email');
            
            $table->unsignedBigInteger('dealer_branch_id')
                  ->nullable()
                  ->after('role')
                  ->comment('NULL for IT staff, filled for dealer users');
            
            $table->boolean('is_active')
                  ->default(true)
                  ->after('dealer_branch_id');
            
            // Foreign key akan ditambahkan setelah tabel dealer_branches dibuat
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'dealer_branch_id', 'is_active']);
        });
    }
};