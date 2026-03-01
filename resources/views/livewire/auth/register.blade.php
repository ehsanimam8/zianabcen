<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

#[Layout('components.layouts.app')]
new class extends Component {
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    
    // Extended fields
    public $phone = '';
    public $address = '';
    public $gender = '';
    public $previous_secular_education = '';
    public $previous_religious_education = '';

    public function register()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'gender' => ['required', 'in:male,female'],
            'previous_secular_education' => ['nullable', 'string', 'max:255'],
            'previous_religious_education' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'previous_secular_education' => $validated['previous_secular_education'],
            'previous_religious_education' => $validated['previous_religious_education'],
        ]);

        $user->assignRole('Student');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended('/portal/dashboard');
    }
}; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-zinc-50">
    <div class="w-full max-w-2xl space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-zinc-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-zinc-900">
                Register as a Student
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-600">
                Already have an account?
                <a href="{{ route('login') }}" wire:navigate class="font-medium text-primary-600 hover:text-primary-500 transition">
                    Sign in here
                </a>
            </p>
        </div>
        <form wire:submit="register" class="mt-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-zinc-900 border-b pb-2">Basic Info</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Full Name</label>
                        <input wire:model="name" type="text" required class="mt-1 block w-full appearance-none rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Email Address</label>
                        <input wire:model="email" type="email" required class="mt-1 block w-full appearance-none rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                        @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Password</label>
                        <input wire:model="password" type="password" required class="mt-1 block w-full appearance-none rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                        @error('password') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password" required class="mt-1 block w-full appearance-none rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                    </div>
                </div>

                <!-- Extended Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-zinc-900 border-b pb-2">Extended Profiling</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700">Gender</label>
                            <select wire:model="gender" required class="mt-1 block w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-primary-500 focus:outline-none focus:ring-primary-500">
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('gender') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700">Phone</label>
                            <input wire:model="phone" type="text" required class="mt-1 block w-full appearance-none rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                            @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Full Address</label>
                        <textarea wire:model="address" rows="2" required class="mt-1 block w-full rounded-md border border-zinc-300 px-3 py-2 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm"></textarea>
                        @error('address') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Highest Secular Education</label>
                        <input wire:model="previous_secular_education" type="text" placeholder="e.g. BS Computer Science" class="mt-1 block w-full appearance-none rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Previous Islamic Education</label>
                        <textarea wire:model="previous_religious_education" rows="2" placeholder="Briefly describe any past studies" class="mt-1 block w-full rounded-md border border-zinc-300 px-3 py-2 focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm"></textarea>
                    </div>

                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="group relative flex w-full justify-center rounded-md border border-transparent bg-primary-600 py-3 px-4 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                    Register and Continue
                </button>
            </div>
        </form>
    </div>
</div>
