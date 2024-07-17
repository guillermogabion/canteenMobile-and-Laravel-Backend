<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('add-admin-and-user', function () {
    $adminData = [
        'firstName' => "Ian",
        'lastName' => "Gabion",
        'address' => "N/A",
        'name' => "Ian Gabion",
        'email' => "gabionguillermo@gmail.com",
        'password' => "Password01!",
        'role' => "admin",
    ];

    $userData = [
        'firstName' => "John",
        'lastName' => "Doe",
        'address' => "N/A",
        'name' => "John Doe",
        'email' => "johndoe@example.com",
        'password' => "Password01!",
        'role' => "user",
    ];

    $users = [$adminData, $userData];

    foreach ($users as $data) {
        if (User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'address' => $data['address'],
            'role' => $data['role'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ])) {
            $this->comment("{$data['role']} added successfully.");
        } else {
            $this->error("Failed to add {$data['role']}.");
        }
    }
})->purpose('-- Add Admin and User');
