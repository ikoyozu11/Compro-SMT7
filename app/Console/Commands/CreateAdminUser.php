<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {username} {password} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Check if user already exists
        if (User::where('username', $username)->exists()) {
            $this->error("User with username '{$username}' already exists!");
            return 1;
        }

        // Create new admin user
        User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'name' => $name,
            'role' => 'admin',
            'status' => 1
        ]);

        $this->info("Admin user '{$username}' created successfully!");
        $this->info("Username: {$username}");
        $this->info("Password: {$password}");
        $this->info("Name: {$name}");

        return 0;
    }
} 