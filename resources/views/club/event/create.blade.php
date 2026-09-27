{{-- resources/views/club/event/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Event - ' . $club->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('club.dashboard', $club) }}" class="text-indigo-600 hover:text-indigo-700 flex items-center mb-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to {{ $club->name }}
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
        <p class="text-gray-600 mt-2">Organize an amazing event for your club members</p>
    </div>

    <!-- Event Form -->
    <form 
        action="{{ route('club.event.store', $club) }}" 
        method="POST" 
        class="bg-white rounded-xl shadow-md p-6 space-y-6"
        x-data="{
            country: '',
            state: '',
            countries: [],
            states: [],
            cities: [],
            async loadCountries() {
                const res = await fetch('/api/countries');
                const data = await res.json();
                this.countries = data.countries;
            },
            async loadStates() {
                if (!this.country) return;
                const res = await fetch(`/api/countries/${this.country}/states`);
                const data = await res.json();
                this.states = data.states;
                this.cities = [];
                this.state = '';
            },
            async loadCities() {
                if (!this.state) return;
                const res = await fetch(`/api/states/${this.state}/cities`);
                const data = await res.json();
                this.cities = data.cities;
            }
        }"
        x-init="loadCountries()"
    >
        @csrf

        <!-- Event Details -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Event Details</h3>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Event Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required 
                        maxlength="50"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Summer Music Festival"
                    >
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        required 
                        maxlength="255"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"
                        placeholder="Describe your event..."
                    >{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Maximum 255 characters</p>
                </div>

                <div>
                    <label for="host_id" class="block text-sm font-medium text-gray-700 mb-1">Event Host *</label>
                    <select 
                        id="host_id" 
                        name="host_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                        <option value="">Select a host</option>
                        @foreach($club->members as $member)
                            <option value="{{ $member->id }}" {{ old('host_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->first_name }} {{ $member->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date *</label>
                        <input 
                            type="date" 
                            id="event_date" 
                            name="event_date" 
                            required
                            value="{{ old('event_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                    </div>

                    <div>
                        <label for="event_time" class="block text-sm font-medium text-gray-700 mb-1">Event Time *</label>
                        <input 
                            type="time" 
                            id="event_time" 
                            name="event_time" 
                            required
                            value="{{ old('event_time') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <div>
                    <label for="ticket_cost" class="block text-sm font-medium text-gray-700 mb-1">Ticket Price (₹)</label>
                    <input 
                        type="number" 
                        id="ticket_cost" 
                        name="ticket_cost" 
                        min="0" 
                        step="0.01"
                        value="{{ old('ticket_cost', '50.00') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="50.00"
                    >
                    <p class="text-xs text-gray-500 mt-1">Default: ₹50.00</p>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Event Location</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                    <select 
                        id="country_id" 
                        name="country_id" 
                        required
                        x-model="country"
                        @change="loadStates()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                        <option value="">Select Country</option>
                        <template x-for="c in countries" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="state_id" class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                    <select 
                        id="state_id" 
                        name="state_id" 
                        required
                        x-model="state"
                        @change="loadCities()"
                        :disabled="!country"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:bg-gray-100"
                    >
                        <option value="">Select State</option>
                        <template x-for="s in states" :key="s.id">
                            <option :value="s.id" x-text="s.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                    <select 
                        id="city_id" 
                        name="city_id" 
                        required
                        :disabled="!state"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:bg-gray-100"
                    >
                        <option value="">Select City</option>
                        <template x-for="c in cities" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        required 
                        maxlength="255"
                        value="{{ old('address') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="123 Main Street"
                    >
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
            <a href="{{ route('club.dashboard', $club) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button 
                type="submit" 
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md hover:shadow-lg"
            >
                Create Event
            </button>
        </div>
    </form>
</div>
@endsection