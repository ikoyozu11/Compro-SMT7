<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Birth date column already exists in the main users table migration
        // This migration is no longer needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed since birth_date was added in the main migration
    }
};
