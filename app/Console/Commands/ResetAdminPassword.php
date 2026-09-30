<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email} {--generate-password}';

    protected $description = 'Pulihkan password akun admin dari terminal terpercaya';

    public function handle(): int
    {
        $user = User::where('email', Str::lower($this->argument('email')))->where('is_admin', true)->first();
        if (! $user) {
            $this->error('Akun admin tidak ditemukan.');

            return self::FAILURE;
        }
        $password = $this->option('generate-password') ? Str::password(24) : $this->secret('Password baru');
        $validator = Validator::make(['password' => $password], ['password' => ['required', 'max:200', Password::min(12)->letters()->numbers()]]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }
        $user->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
        $this->info('Password admin berhasil diperbarui.');
        if ($this->option('generate-password')) {
            $this->line('Password: '.$password);
        }

        return self::SUCCESS;
    }
}
