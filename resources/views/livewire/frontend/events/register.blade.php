<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\CMS\Event;
use App\Models\CMS\EventRegistration;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.frontend')] class extends Component {
    public $event;
    
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';

    public $success = false;

    public function mount($slug)
    {
        $this->event = Event::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        if ($this->event->status === 'Past') {
            abort(404, 'Event is no longer active.');
        }
    }
    
    public function rendering($view)
    {
        $view->title('Register - ' . $this->event->title . ' | Zainab Center');
    }

    public function register()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if (!$this->event->hasCapacity()) {
            $this->addError('capacity', 'Sorry, this event is already at full capacity.');
            return;
        }

        DB::transaction(function () {
            EventRegistration::create([
                'event_id' => $this->event->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'registered_at' => now(),
            ]);

            $this->event->increment('current_registrations');
        });

        $this->success = true;
    }
}; ?>

<div class="bg-zinc-50 py-16 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('frontend.events.show', $event->slug ?? $event->id) }}" class="inline-flex items-center text-sm font-medium text-zinc-500 hover:text-zinc-900 mb-8 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Event Details
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <h1 class="text-3xl font-bold text-zinc-900 mb-2">Register for Event</h1>
                <p class="text-zinc-500 mb-8 text-lg font-medium">{{ $event->title }}</p>

                @if($success)
                    <div class="rounded-xl bg-green-50 p-6 border border-green-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Registration Successful!</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>JazakAllah Khair! You have successfully registered for this event. We look forward to seeing you.</p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('frontend.events.show', $event->slug ?? $event->id) }}" class="text-sm font-medium text-green-800 hover:text-green-900">
                                        &larr; Return to Event Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(!$event->hasCapacity())
                    <div class="rounded-xl bg-red-50 p-6 border border-red-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Event Full</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Sorry, this event has reached its maximum capacity.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form wire:submit="register" class="space-y-6">
                        @error('capacity')
                            <div class="rounded-md bg-red-50 p-4 border border-red-200 text-sm text-red-700">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-zinc-700">First Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="first_name" id="first_name" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm h-11 border px-3">
                                @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-zinc-700">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="last_name" id="last_name" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm h-11 border px-3">
                                @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-zinc-700">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="email" id="email" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm h-11 border px-3">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="phone" class="block text-sm font-medium text-zinc-700">Phone Number</label>
                                <input type="tel" wire:model="phone" id="phone" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm h-11 border px-3">
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex items-center bg-zinc-50 -mx-8 px-8 sm:-mx-12 sm:px-12 -mb-8 pb-8 pt-6 border-t border-zinc-100 justify-between">
                            <div class="text-sm text-zinc-500">
                                <span class="text-red-500">*</span> Indicates required fields
                            </div>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-primary-800 py-3 px-6 text-sm font-bold text-white shadow-sm hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                                Complete Registration
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
