<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Communication\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
new class extends Component {
    public $messages = [];
    public $isComposing = false;
    
    public $recipient_id;
    public $subject;
    public $body;

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Message::where('recipient_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRecipientsProperty()
    {
        return User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'admin', 'Instructor', 'instructor', 'Super Admin', 'super_admin']);
        })->where('id', '!=', Auth::id())->orderBy('name')->get();
    }

    public function sendMessage()
    {
        $this->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $this->recipient_id,
            'subject' => $this->subject,
            'body' => $this->body,
        ]);

        $this->reset(['recipient_id', 'subject', 'body', 'isComposing']);
        $this->loadMessages();
        
        session()->flash('message', 'Message sent successfully!');
    }
}; ?>

<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-zinc-900">Messages</h1>
        <button wire:click="$toggle('isComposing')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            {{ $isComposing ? 'Cancel Compose' : 'Compose Message' }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 text-green-700 border border-green-200 p-4 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if($isComposing)
        <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-200 mb-6">
            <h2 class="text-lg font-semibold text-zinc-900 mb-4">New Message</h2>
            <form wire:submit="sendMessage" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">To</label>
                    <select wire:model="recipient_id" class="w-full border-zinc-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select a user...</option>
                        @foreach($this->recipients as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->roles->first()?->name ?? 'Staff' }}</option>
                        @endforeach
                    </select>
                    @error('recipient_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Subject</label>
                    <input type="text" wire:model="subject" class="w-full border-zinc-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    @error('subject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Message</label>
                    <textarea wire:model="body" rows="4" class="w-full border-zinc-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                    @error('body') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
        @if($messages->isEmpty())
            <div class="p-8 text-center text-zinc-500">
                You have no messages yet.
            </div>
        @else
            <ul class="divide-y divide-zinc-200">
                @foreach($messages as $msg)
                    <li class="p-4 hover:bg-zinc-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="h-10 w-10 flex items-center justify-center rounded-full text-white font-bold {{ $msg->sender_id === auth()->id() ? 'bg-zinc-800' : 'bg-primary-600' }}">
                                    {{ substr($msg->sender_id === auth()->id() ? $msg->recipient?->name : $msg->sender?->name, 0, 1) }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900">
                                        {{ $msg->sender_id === auth()->id() ? 'To: ' . $msg->recipient?->name : 'From: ' . $msg->sender?->name }}
                                    </p>
                                    <p class="text-sm text-zinc-500 truncate">{{ $msg->subject }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-zinc-400">{{ $msg->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-zinc-700">
                            {{ $msg->body }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
