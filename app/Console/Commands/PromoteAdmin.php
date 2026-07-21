<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
class PromoteAdmin extends Command {
    protected $signature='admin:promote {email}'; protected $description='Grant admin role to an existing registered user';
    public function handle(): int { $user=User::where('email',$this->argument('email'))->first(); if(!$user){$this->error('User not found. Register first.');return self::FAILURE;} $user->update(['is_admin'=>true]);$this->info($user->email.' is now an admin.');return self::SUCCESS; }
}
