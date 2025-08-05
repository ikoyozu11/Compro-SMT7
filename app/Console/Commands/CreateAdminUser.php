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
    protected $signature = 'admin:create {username} {password} {name} {phone?}';

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
        $phone = $this->argument('phone');

        // Validate password requirements
        if (strlen($password) < 6) {
            $this->error("Password must be at least 6 characters long!");
            return 1;
        }

        if (!preg_match('/[a-zA-Z]/', $password)) {
            $this->error("Password must contain at least one letter!");
            return 1;
        }

        if (!preg_match('/[0-9]/', $password)) {
            $this->error("Password must contain at least one number!");
            return 1;
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $this->error("Password must contain at least one symbol!");
            return 1;
        }

        // Validate phone number if provided
        $phone = $this->argument('phone');
        if ($phone && (!preg_match('/^[0-9]+$/', $phone) || strlen($phone) < 11 || strlen($phone) > 12)) {
            $this->error("Phone number must contain only numbers and be 11-12 digits long!");
            return 1;
        }

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
            'phone' => $phone,
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