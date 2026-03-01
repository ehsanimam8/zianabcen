<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\SIS\Course;
use App\Models\CMS\Setting;
use Illuminate\Support\Facades\Session;

new #[Layout('components.layouts.frontend', ['title' => 'Shopping Cart | Zainab Center', 'description' => 'Securely checkout and enroll in your selected Islamic programs at Zainab Center.'])] class extends Component {
    public $cartItems = [];
    public $subtotal = 0;
    public $studentAssignments = [];
    
    // Stripe settings check
    public $stripeConfigured = false;
    public $stripeKey = '';

    public function mount()
    {
        $this->stripeKey = Setting::where('key', 'stripe_public_key')->value('value');
        $this->stripeConfigured = !empty($this->stripeKey);

        $this->loadCart();
    }
    
    public function loadCart()
    {
        $cartIds = Session::get('cart', []);
        $this->cartItems = Course::whereIn('id', $cartIds)->get();
        foreach ($this->cartItems as $item) {
            if (!isset($this->studentAssignments[$item->id])) {
                $this->studentAssignments[$item->id] = auth()->user()->name ?? 'Guest';
            }
        }
        $this->calculateTotal();
    }
    
    public function calculateTotal()
    {
        $this->subtotal = $this->cartItems->sum('price');
    }
    
    public function removeItem($courseId)
    {
        $cartIds = Session::get('cart', []);
        $cartIds = array_filter($cartIds, fn($id) => $id != $courseId);
        Session::put('cart', $cartIds);
        unset($this->studentAssignments[$courseId]);
        
        $this->loadCart();
    }
    
    public function checkout()
    {
        if (!$this->stripeConfigured) return;

        \Stripe\Stripe::setApiKey(Setting::where('key', 'stripe_secret_key')->value('value'));
        
        $lineItems = [];
        $currency = Setting::where('key', 'stripe_currency')->value('value') ?? 'USD';

        foreach($this->cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => (int)($item->price * 100),
                ],
                'quantity' => 1,
            ];
        }

        $userId = auth()->id() ?? '';

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('home') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('frontend.cart'),
                'metadata' => [
                    'course_ids' => implode(',', $this->cartItems->pluck('id')->toArray()),
                    'user_id' => $userId,
                    'is_family_billing' => 1,
                    'student_assignments' => json_encode($this->studentAssignments),
                ],
            ]);

            return $this->redirect($session->url);
        } catch (\Exception $e) {
            $this->js("alert('Error configuring Stripe checkout: " . addslashes($e->getMessage()) . "')");
        }
    }
}; ?>

<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900">Shopping Cart</h1>
            <p class="mt-2 text-zinc-500">Review your program selections before checkout.</p>
        </div>
        <div class="hidden sm:flex items-center text-primary-600 bg-primary-50 px-4 py-2 rounded-lg font-medium text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            {{ count($cartItems) }} Items
        </div>
    </div>

    @if(!$stripeConfigured)
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-700 font-medium">
                        Payment gateway is not currently configured. 
                    </p>
                    <p class="mt-1 text-sm text-amber-600">
                        Administrators must configure the Stripe API Credentials in the Admin portal before checkouts can be processed safely.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(count($cartItems) === 0)
        <!-- Empty Cart -->
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-12 text-center">
            <div class="w-24 h-24 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-zinc-900 mb-2">Your cart is empty</h3>
            <p class="text-zinc-500 mb-8 max-w-sm mx-auto">Looks like you haven't added any courses to your cart yet.</p>
            <a href="{{ route('frontend.programs.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 shadow-sm transition-colors">
                Browse Programs & Courses
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between group">
                        <div class="flex-1 pr-6">
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $item->level ?? 'Course' }}
                                </span>
                                <span class="ml-3 text-xs text-zinc-500 font-medium border border-zinc-200 px-2 py-0.5 rounded">
                                    {{ Str::title(str_replace('_', ' ', $item->billing_cycle)) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 leading-tight mb-1">{{ $item->name }}</h3>
                            <p class="text-sm text-zinc-500 line-clamp-2">{{ strip_tags($item->description) }}</p>
                        </div>
                        
                        <div class="mt-4 sm:mt-0 flex flex-col items-end justify-between w-full sm:w-auto border-t sm:border-0 border-zinc-100 pt-4 sm:pt-0">
                            <div class="text-2xl font-bold text-zinc-900 mb-2">${{ number_format($item->price, 2) }}</div>
                            <div class="mb-4 text-right w-full">
                                <label class="block text-xs font-semibold text-zinc-500 mb-1">Assign to Student:</label>
                                <input type="text" wire:model.live="studentAssignments.{{ $item->id }}" class="w-full sm:w-48 text-sm px-3 py-1.5 border border-zinc-300 rounded focus:ring-primary-500 focus:border-primary-500 shadow-sm" placeholder="Student Name">
                            </div>
                            <button wire:click="removeItem('{{ $item->id }}')" class="text-sm font-medium text-red-500 hover:text-red-700 flex items-center transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                        <h3 class="text-lg font-bold text-zinc-900">Order Summary</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center text-zinc-600">
                            <span>Subtotal</span>
                            <span class="font-medium text-zinc-900">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-zinc-600">
                            <span>Platform Fee</span>
                            <span class="font-medium text-green-600">Waived</span>
                        </div>
                        
                        <div class="pt-4 border-t border-zinc-100 border-dashed mb-6 flex justify-between items-center">
                            <span class="text-base font-bold text-zinc-900">Total</span>
                            <span class="text-2xl font-bold text-primary-600">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <button 
                            wire:click="checkout" 
                            @if(!$stripeConfigured) disabled @endif
                            class="w-full flex items-center justify-center px-6 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed group relative overflow-hidden"
                        >
                            <span class="relative z-10 flex items-center">
                                Proceed to Checkout
                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </button>
                        
                        <div class="mt-4 flex items-center justify-center space-x-2 text-zinc-400">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                            <span class="text-xs">Secure SSL encrypted payment</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
