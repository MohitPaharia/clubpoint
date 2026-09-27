{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register - ClubHub')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Create Your Account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Join ClubHub and start organizing amazing events
            </p>
        </div>
        
        <form 
            class="bg-white p-8 rounded-lg shadow-md space-y-6" 
            action="{{ route('register.perform') }}" 
            method="POST"
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
            
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input 
                            id="first_name" 
                            name="first_name" 
                            type="text" 
                            required 
                            value="{{ old('first_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="John"
                        >
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input 
                            id="last_name" 
                            name="last_name" 
                            type="text" 
                            required 
                            value="{{ old('last_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Doe"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="john.doe@example.com"
                        >
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input 
                            id="phone_number" 
                            name="phone_number" 
                            type="tel" 
                            required 
                            value="{{ old('phone_number') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="+91 98765 43210"
                        >
                    </div>
                </div>
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

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input 
                            id="address" 
                            name="address" 
                            type="text" 
                            required 
                            value="{{ old('address') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="123 Main Street"
                        >
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Minimum 8 characters"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Re-enter your password"
                        >
                    </div>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-md hover:shadow-lg"
                >
                    Create Account
                </button>
            </div>

            <div class="text-center text-sm">
                <span class="text-gray-600">Already have an account?</span>
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 ml-1">
                    Sign in here
                </a>
            </div>
        </form>
    </div>
</div>
@endsection