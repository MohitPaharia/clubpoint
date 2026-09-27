{{-- resources/views/club/event/show.blade.php --}}
@extends('layouts.app')

@section('title', $event->name . ' - ClubHub')

@section('content')
<div x-data="{
    editing: false,
    purchasing: false,
    
    async purchaseTicket() {
        if (!confirm('Purchase ticket for ₹{{ $event->ticket_cost }}?')) return;
        
        this.purchasing = true;
        try {
            const res = await fetch('/clubs/{{ $event->club_id }}/events/{{ $event->id }}/purchase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const data = await res.json();
            
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Purchase failed');
            }
        } catch (error) {
            alert('Error purchasing ticket');
        } finally {
            this.purchasing = false;
        }
    },
    
    async deleteEvent() {
        if (!confirm('Delete this event? This action cannot be undone.')) return;
        
        try {
            const res = await fetch('/clubs/{{ $event->club_id }}/events/{{ $event->id }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const data = await res.json();
            
            if (data.success) {
                alert(data.message);
                window.location.href = '/clubs/{{ $event->club_id }}/dashboard';
            }
        } catch (error) {
            alert('Error deleting event');
        }
    }
}" class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <a href="{{ route('club.dashboard', $event->club) }}" class="text-indigo-600 hover:text-indigo-700 flex items-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to {{ $event->club->name }}
    </a>

    <!-- Event Header -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="h-48 bg-gradient-to-r from-purple-600 via-pink-600 to-red-600"></div>
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->name }}</h1>
                    <p class="text-gray-600">{{ $event->description }}</p>
                </div>
                
                @if(auth()->id() === $event->club->owner_id)
                    <div class="relative ml-4" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <button @click="editing = true; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit Event
                            </button>
                            <button @click="deleteEvent(); open = false" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Delete Event
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Event Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                    <div class="flex items-center text-indigo-600 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium">Date</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                </div>

                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center text-purple-600 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Time</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</p>
                </div>

                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center text-green-600 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Ticket Price</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">₹{{ number_format($event->ticket_cost, 2) }}</p>
                </div>

                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <div class="flex items-center text-pink-600 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <span class="text-sm font-medium">Tickets Sold</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ $event->participants->count() }}</p>
                </div>
            </div>

            <!-- Host Info -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Hosted by</p>
                        <p class="font-semibold text-gray-900">{{ $event->host->first_name }} {{ $event->host->last_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $event->address }}</p>
                        <p class="text-sm text-gray-600">
                            {{ optional($event->city)->name }},
                            {{ optional($event->state)->name }},
                            {{ optional($event->country)->name }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Purchase Button -->
            @if(auth()->check() && !$event->participants->contains('user_id', auth()->id()))
                <div class="mt-6">
                    <button 
                        @click="purchaseTicket()"
                        :disabled="purchasing"
                        class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!purchasing">Purchase Ticket - ₹{{ number_format($event->ticket_cost, 2) }}</span>
                        <span x-show="purchasing" x-cloak>Processing...</span>
                    </button>
                </div>
            @elseif(auth()->check() && $event->participants->contains('user_id', auth()->id()))
                <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold text-green-900">You have a ticket for this event!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editing" 
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         @click.self="editing = false"
    >
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Edit Event</h2>
                <button @click="editing = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('club.event.update', [$event->club, $event]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Event Name</label>
                    <input 
                        type="text" 
                        id="edit_name" 
                        name="name" 
                        required 
                        maxlength="50"
                        value="{{ $event->name }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea 
                        id="edit_description" 
                        name="description" 
                        required 
                        maxlength="255"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                    >{{ $event->description }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                        <input 
                            type="date" 
                            id="edit_event_date" 
                            name="event_date" 
                            required
                            value="{{ $event->event_date }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="edit_event_time" class="block text-sm font-medium text-gray-700 mb-1">Event Time</label>
                        <input 
                            type="time" 
                            id="edit_event_time" 
                            name="event_time" 
                            required
                            value="{{ $event->event_time }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>
                </div>

                <div>
                    <label for="edit_ticket_cost" class="block text-sm font-medium text-gray-700 mb-1">Ticket Price (₹)</label>
                    <input 
                        type="number" 
                        id="edit_ticket_cost" 
                        name="ticket_cost" 
                        min="0" 
                        step="0.01"
                        value="{{ $event->ticket_cost }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <input type="hidden" name="host_id" value="{{ $event->host_id }}">
                <input type="hidden" name="address" value="{{ $event->address }}">
                <input type="hidden" name="city_id" value="{{ $event->city_id }}">
                <input type="hidden" name="state_id" value="{{ $event->state_id }}">
                <input type="hidden" name="country_id" value="{{ $event->country_id }}">

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                    <button 
                        type="button"
                        @click="editing = false"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendees -->
    @if($event->participants->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Attendees ({{ $event->participants->count() }})</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($event->participants as $user)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                            <p class="text-sm text-gray-600 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection