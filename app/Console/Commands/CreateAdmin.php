<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email} {--name=Administrator} {--generate-password}';

    protected $description = 'Buat administrator tanpa registrasi publik atau password default';

    public function handle(): int
    {
        $email = Str::lower($this->argument('email'));
        $password = $this->option('generate-password') ? Str::password(24) : $this->secret('Password (minimal 12 karakter, huruf dan angka)');
        $data = ['name' => $this->option('name'), 'email' => $email, 'password' => $password];
        $validator = Validator::make($data, ['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email', 'password' => ['required', 'max:200', Password::min(12)->letters()->numbers()]]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }
        $user = new User($data);
        $user->is_admin = true;
        $user->save();
        $this->info('Admin dibuat: '.$email);
        if ($this->option('generate-password')) {
            $this->line('Password: '.$password);
        }

        return self::SUCCESS;
    }
}
