<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
new class extends Component {
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            
            // Redirect based on role
            if (Auth::user()->hasRole(['Admin', 'Super Admin'])) {
                return redirect()->to('/admin');
            } elseif (Auth::user()->hasRole(['Instructor'])) {
                return redirect()->to('/teacher');
            }
            
            return redirect()->intended('/portal/dashboard');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
}; ?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-zinc-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-zinc-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-600">
                Or
                <a href="{{ route('student.register') }}" wire:navigate class="font-medium text-primary-600 hover:text-primary-500 transition">
                    register a new student profile
                </a>
            </p>
        </div>
        <form wire:submit="login" class="mt-8 space-y-6">
            <div class="-space-y-px rounded-md shadow-sm">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input wire:model="email" id="email-address" name="email" type="email" autocomplete="email" required class="relative block w-full appearance-none rounded-none rounded-t-md border border-zinc-300 px-3 py-3 text-zinc-900 placeholder-zinc-500 focus:z-10 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm" placeholder="Email address">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input wire:model="password" id="password" name="password" type="password" autocomplete="current-password" required class="relative block w-full appearance-none rounded-none rounded-b-md border border-zinc-300 px-3 py-3 text-zinc-900 placeholder-zinc-500 focus:z-10 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm" placeholder="Password">
                </div>
            </div>
            
            @error('email') <div class="text-sm text-red-600 font-medium">{{ $message }}</div> @enderror

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="remember" id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-primary-600 focus:ring-primary-500">
                    <label for="remember-me" class="ml-2 block text-sm text-zinc-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-primary-600 hover:text-primary-500">Forgot your password?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative flex w-full justify-center rounded-md border border-transparent bg-primary-600 py-3 px-4 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
