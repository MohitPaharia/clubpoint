{{-- resources/views/clubs/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Club - ClubHub')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('profile') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Profile
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create a New Club</h1>
        <p class="text-gray-600 mt-2">Start your own community and bring people together around shared interests.</p>
    </div>

    <!-- Create Club Form -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('club.store') }}" method="POST" class="space-y-6"
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

            <!-- Club Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Club Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    maxlength="50"
                    required
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                    placeholder="e.g., Photography Enthusiasts, Book Club, Gaming Guild"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maximum 50 characters</p>
            </div>

            <!-- Club Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4"
                    maxlength="255"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                    placeholder="Describe what your club is about, what activities you'll do, and who should join..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maximum 255 characters</p>
            </div>
            
            <!-- Location Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <select 
                            id="country" 
                            name="country" 
                            required
                            x-model="country"
                            @change="loadStates()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                            <option value="">Select Country</option>
                            <template x-for="c in countries" :key="c.id">
                                <option :value="c.id" x-text="c.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <select 
                            id="state" 
                            name="state" 
                            required
                            x-model="state"
                            @change="loadCities()"
                            :disabled="!country"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:bg-gray-100"
                        >
                            <option value="">Select State</option>
                            <template x-for="s in states" :key="s.id">
                                <option :value="s.id" x-text="s.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <select 
                            id="city" 
                            name="city" 
                            required
                            :disabled="!state"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:bg-gray-100"
                        >
                            <option value="">Select City</option>
                            <template x-for="c in cities" :key="c.id">
                                <option :value="c.id" x-text="c.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-900">Club Approval Process</h4>
                        <p class="mt-1 text-sm text-blue-700">Your club will be reviewed by our team before it becomes active. You'll receive a notification once it's approved.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a 
                    href="{{ route('profile') }}" 
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
                <button 
                    type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-md hover:shadow-lg"
                >
                    Create Club
                </button>
            </div>
        </form>
    </div>

    <!-- Tips Section -->
    <div class="mt-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">💡 Tips for Creating a Great Club</h3>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="text-indigo-600 mr-2">•</span>
                <span>Choose a clear, descriptive name that tells people what your club is about</span>
            </li>
            <li class="flex items-start">
                <span class="text-indigo-600 mr-2">•</span>
                <span>Write a welcoming description that explains your club's purpose and activities</span>
            </li>
            <li class="flex items-start">
                <span class="text-indigo-600 mr-2">•</span>
                <span>Be specific about the location to help local members find you</span>
            </li>
            <li class="flex items-start">
                <span class="text-indigo-600 mr-2">•</span>
                <span>Set clear expectations for members and be ready to engage with your community</span>
            </li>
        </ul>
    </div>
</div>
@endsection