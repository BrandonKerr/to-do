<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::create([
            "name" => "Awesome Admin",
            "email" => "admin@todo.com",
            "password" => Hash::make("admin"),
            "email_verified_at" => now(),
            "is_admin" => true,
        ]);

        $this->command->info("Admin user created");

        User::create([
            "name" => "Normal User",
            "email" => "user@todo.com",
            "password" => Hash::make("user"),
            "email_verified_at" => now(),
        ]);

        $this->command->info("Standard user created");
    }
}
