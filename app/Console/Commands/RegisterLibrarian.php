<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Models\Librarian;

class RegisterLibrarian extends Command
{
    protected $signature = 'librarian:register';

    protected $description = 'Register a new librarian';

    public function handle()
    {
        $name = $this->ask('Name:');
        $email = $this->ask('Email:');
        $password = $this->secret('Password:');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:librarians',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }

        Librarian::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->info('Librarian successfully registered');
    }
}