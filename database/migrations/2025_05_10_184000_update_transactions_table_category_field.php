<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // First, make the category column nullable
            $table->string('category')->nullable()->change();
            
            // Then, update existing records to use the category name from the categories table
            DB::statement('UPDATE transactions t 
                INNER JOIN categories c ON t.category_id = c.id 
                SET t.category = c.name 
                WHERE t.category_id IS NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });
    }
}; 