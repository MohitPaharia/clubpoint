{{-- resources/views/authentication/expired-url.blade.php --}}
@extends('layouts.app')

@section('title', 'Verification Link Expired - ClubHub')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Content -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Verification Link Expired</h1>
            <p class="text-gray-600 mb-6">
                The verification link you clicked has expired or is invalid. This can happen if the link is too old or has already been used.
            </p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-semibold text-yellow-900 mb-2">What can you do?</h3>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Register again with your email</li>
                    <li>• Check for a newer verification email</li>
                    <li>• Contact our support team</li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register.show') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                    Register Again
                </a>
                <a href="{{ route('login') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection