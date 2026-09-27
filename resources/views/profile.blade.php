{{-- resources/views/profile.blade.php --}}
@extends('layouts.app')

@section('title', 'My Profile - ClubHub')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
        <div class="px-6 pb-6">
            <div class="flex items-end -mt-16 mb-4">
                <div class="w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-4xl border-4 border-white shadow-lg">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                </div>
                <div class="ml-6 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </h1>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Account Balance</div>
                    <div class="text-2xl font-bold text-green-600">₹{{ number_format(auth()->user()->credit ?? 0, 2) }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Phone Number</div>
                    <div class="text-lg font-semibold text-gray-900">{{ auth()->user()->phone_number }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Location</div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ auth()->user()->city?->name ?? 'N/A' }},
                        {{ auth()->user()->state?->name ?? 'N/A' }},
                        {{ auth()->user()->country?->name ?? 'N/A' }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'clubs' }" class="bg-white rounded-xl shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button 
                    @click="activeTab = 'clubs'"
                    :class="activeTab === 'clubs' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition"
                >
                    My Clubs
                </button>
                <button 
                    @click="activeTab = 'events'"
                    :class="activeTab === 'events' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition"
                >
                    My Events
                </button>
                <button 
                    @click="activeTab = 'tickets'"
                    :class="activeTab === 'tickets' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition"
                >
                    My Tickets
                </button>
                <button 
                    @click="activeTab = 'settings'"
                    :class="activeTab === 'settings' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition"
                >
                    Settings
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- My Clubs Tab -->
            <div x-show="activeTab === 'clubs'" x-cloak>
                <!-- Create Club Button -->
                <div class="mb-6">
                    <a href="{{ route('club.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Club
                    </a>
                </div>

                @if(auth()->user()->ownedClubs->count() > 0 || auth()->user()->clubs->count() > 0)
                    <div class="space-y-6">
                        @if(auth()->user()->ownedClubs->count() > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Clubs I Own</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach(auth()->user()->ownedClubs as $club)
                                        <a href="{{ route('club.dashboard', $club) }}" class="block bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 hover:shadow-md transition border border-indigo-200">
                                            <div class="flex items-start justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900">{{ $club->name }}</h4>
                                                <span class="bg-indigo-600 text-white text-xs px-2 py-1 rounded">Owner</span>
                                            </div>
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $club->description }}</p>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                {{ $club->members->count() }} members
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->clubs->reject(fn ($club) => $club->owner_id == auth()->id())->count() > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Clubs I'm In</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach(auth()->user()->clubs->reject(fn ($club) => $club->owner_id == auth()->id()) as $club)
                                        <a href="{{ route('club.dashboard', $club) }}" class="block bg-gray-50 rounded-lg p-4 hover:shadow-md transition border border-gray-200">
                                            <h4 class="font-semibold text-gray-900 mb-2">{{ $club->name }}</h4>
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $club->description }}</p>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                {{ $club->members->count() }} members
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No clubs yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating or joining a club.</p>
                        <div class="mt-6">
                            <a href="/" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Discover Clubs
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- My Events Tab -->
            <div x-show="activeTab === 'events'" x-cloak>
                @if(auth()->user()->hostedEvents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(auth()->user()->hostedEvents as $event)
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $event->name }}</h4>
                                    <span class="bg-indigo-600 text-white text-xs px-2 py-1 rounded">Host</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $event->description }}</p>
                                <div class="text-xs text-gray-500 space-y-1">
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->address }}
                                    </p>
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ₹{{ number_format($event->ticket_cost, 2) }}
                                    </p>
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        {{ $event->participants->count() }} participants
                                    </p>
                                    <p class="text-indigo-600 font-medium">Club: {{ $event->club->name }}</p>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('club.event.show', [$event->club, $event]) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No events yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Events you create will appear here.</p>
                    </div>
                @endif
            </div>

            <!-- My Tickets Tab -->
            <div x-show="activeTab === 'tickets'" x-cloak>
                @if(auth()->user()->eventParticipations->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(auth()->user()->eventParticipations as $event)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $event->name }}</h4>
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Purchased</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $event->description }}</p>
                                <div class="text-xs text-gray-500 space-y-1">
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->address }}
                                    </p>
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ₹{{ number_format($event->ticket_cost, 2) }}
                                    </p>
                                    <p class="text-green-700 font-medium">Club: {{ $event->club->name }}</p>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('club.event.show', [$event->club, $event]) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                                        View Event →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tickets yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Purchase tickets for events to see them here.</p>
                    </div>
                @endif
            </div>

            <!-- Settings Tab -->
            <div x-show="activeTab === 'settings'" x-cloak>
                <div class="max-w-2xl space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-600">Full Name</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-600">Email</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->email }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-600">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->phone_number }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-600">Address</dt>
                                <dd class="text-sm text-gray-900">{{ auth()->user()->address }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <h3 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h3>
                        <p class="text-sm text-red-700 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                        <button 
                            onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) { document.getElementById('delete-account-form').submit(); }"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition"
                        >
                            Delete Account
                        </button>
                        <form id="delete-account-form" action="{{ route('profile') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection