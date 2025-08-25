<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update test user for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'admin@test.com';
        $password = 'password';
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $user->password = Hash::make($password);
            $user->save();
            $this->info("Updated existing user: {$email}");
        } else {
            $user = User::create([
                'name' => 'Test Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            $this->info("Created new user: {$email}");
        }
        
        $this->info("Login credentials:");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        
        return 0;
    }
}
