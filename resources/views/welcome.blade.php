{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'Discover Clubs - ClubHub')

@section('content')
<div x-data="{
    searchQuery: '',
    searchType: 'clubs',
    results: [],
    loading: false,
    async search() {
        if (this.searchQuery.length < 2) {
            this.results = [];
            return;
        }
        
        this.loading = true;
        try {
            const res = await fetch(`/api/search/${this.searchType}?query=${encodeURIComponent(this.searchQuery)}`);
            const data = await res.json();
            this.results = Array.isArray(data) ? data : (data[this.searchType] || []);
        } catch (error) {
            console.error('Search error:', error);
            this.results = [];
        }
        this.loading = false;
    }
}" class="space-y-8">

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 md:p-12 text-white">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Discover Amazing Clubs
            </h1>
            <p class="text-lg md:text-xl text-indigo-100 mb-8">
                Join communities, attend exciting events, and connect with like-minded people
            </p>
            
            @guest
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register.show') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition shadow-lg hover:shadow-xl">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-800 transition border-2 border-white">
                        Sign In
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-4">
            <div class="flex flex-wrap gap-2 mb-4">
                <button 
                    @click="searchType = 'clubs'; search()"
                    :class="searchType === 'clubs' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-lg font-medium transition"
                >
                    Clubs
                </button>
                <button 
                    @click="searchType = 'events'; search()"
                    :class="searchType === 'events' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-lg font-medium transition"
                >
                    Events
                </button>
                <button 
                    @click="searchType = 'users'; search()"
                    :class="searchType === 'users' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-lg font-medium transition"
                >
                    People
                </button>
            </div>

            <div class="relative">
                <input 
                    type="text" 
                    x-model="searchQuery"
                    @input.debounce.500ms="search()"
                    placeholder="Search for clubs, events, or people..."
                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                
                <div x-show="loading" x-cloak class="absolute right-4 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div x-show="results.length > 0" x-cloak class="mt-6 space-y-4">
            <!-- Club Results -->
            <template x-if="searchType === 'clubs'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="club in results" :key="club.id">
                        <a :href="`/clubs/${club.id}/dashboard`" class="block bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition border border-gray-200 hover:border-indigo-300">
                            <h3 class="font-semibold text-gray-900 mb-1" x-text="club.name"></h3>
                            <p class="text-sm text-gray-600 line-clamp-2" x-text="club.description"></p>
                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span x-text="`${club.members_count || 0} members`"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            <!-- Event Results -->
            <template x-if="searchType === 'events'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="event in results" :key="event.id">
                        <a :href="`/clubs/${event.club_id}/events/${event.id}`" class="block bg-gray-50 rounded-lg p-4 border border-gray-200 hover:bg-gray-100 hover:border-indigo-300 transition">
                            <h3 class="font-semibold text-gray-900 mb-1" x-text="event.name"></h3>
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2" x-text="event.description"></p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span x-text="event.address"></span>
                                <span class="font-semibold text-indigo-600" x-text="`₹${event.ticket_cost}`"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            <!-- User Results -->
            <template x-if="searchType === 'users'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="user in results" :key="user.id">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                <span x-text="`${user.first_name?.[0] || ''}${user.last_name?.[0] || ''}`"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate" x-text="`${user.first_name} ${user.last_name}`"></p>
                                <p class="text-sm text-gray-600 truncate" x-text="user.email"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <div x-show="searchQuery.length >= 2 && results.length === 0 && !loading" x-cloak class="text-center py-8 text-gray-500">
            No results found for "<span x-text="searchQuery"></span>"
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Join Communities</h3>
            <p class="text-gray-600 text-sm">Connect with people who share your interests and passions</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Attend Events</h3>
            <p class="text-gray-600 text-sm">Discover and participate in exciting events organized by clubs</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Organize & Grow</h3>
            <p class="text-gray-600 text-sm">Create your own club and build a thriving community</p>
        </div>
    </div>
</div>
@endsection