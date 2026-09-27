{{-- resources/views/authentication/check-email.blade.php --}}
@extends('layouts.app')

@section('title', 'Verify Your Email - ClubHub')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>

            <!-- Content -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Check Your Email</h1>
            <p class="text-gray-600 mb-6">
                We've sent you a verification email. Please check your inbox and click the verification link to activate your account.
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-semibold text-blue-900 mb-2">What's next?</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>✓ Check your email inbox</li>
                    <li>✓ Click the verification link</li>
                    <li>✓ Start using ClubHub!</li>
                </ul>
            </div>

            <p class="text-sm text-gray-500">
                Didn't receive the email? Check your spam folder or contact support.
            </p>

            <div class="mt-6">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection