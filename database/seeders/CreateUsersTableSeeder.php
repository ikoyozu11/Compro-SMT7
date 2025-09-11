<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create users table if it doesn't exist
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->string('password');
                $table->string('name');
                $table->date('birth_date')->nullable();
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('institution')->nullable();
                $table->string('role')->default('user');
                $table->integer('status')->default(1);
                $table->timestamps();
            });
            
            echo "Users table created successfully!\n";
        } else {
            echo "Users table already exists!\n";
        }
        
        // Add migration record
        DB::table('migrations')->insertOrIgnore([
            'migration' => '2024_09_25_080745_create_user_table',
            'batch' => 1
        ]);
        
        echo "Migration record updated!\n";
    }
}
