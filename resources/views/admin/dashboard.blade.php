{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard - ClubHub')

@section('content')
<div x-data="{
    async approveClub(clubId) {
        if (!confirm('Approve this club?')) return;
        
        try {
            const res = await fetch(`/admin/clubs/${clubId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const data = await res.json();
            
            if (data.success) {
                document.getElementById(`club-${clubId}`).remove();
                x = document.getElementById(`pendingClubsCount`);
                x.innerHtml = x.innerHtml - 1;
                alert(data.message);
            }
        } catch (error) {
            alert('Error approving club');
        }
    },
    
    async rejectClub(clubId) {
        if (!confirm('Reject and delete this club? This cannot be undone.')) return;
        
        try {
            const res = await fetch(`/admin/clubs/${clubId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const data = await res.json();
            
            if (data.success) {
                document.getElementById(`club-${clubId}`).remove();
                alert(data.message);
            }
        } catch (error) {
            alert('Error rejecting club');
        }
    }
}" class="space-y-6">

    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-xl shadow-xl p-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
        <p class="text-orange-100">Manage clubs, users, and platform activities</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Clubs</p>
                    <p class="text-3xl font-bold text-gray-900" id="pendingClubsCount">{{ $clubs->count() }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recent Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $recent_users->count() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Clubs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Club::count() }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Clubs -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Pending Club Approvals</h2>
        </div>
        
        <div class="p-6">
            @if($clubs->count() > 0)
                <div class="space-y-4">
                    @foreach($clubs as $club)
                        <div id="club-{{ $club->id }}" class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $club->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ $club->description }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Owner:</span>
                                            <span class="font-medium text-gray-900 ml-2">
                                                {{ $club->owner->first_name }} {{ $club->owner->last_name }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Email:</span>
                                            <span class="font-medium text-gray-900 ml-2">{{ $club->owner->email }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Location:</span>
                                            <span class="font-medium text-gray-900 ml-2">
                                                {{ $club->city->name ?? 'N/A' }}, {{ $club->state->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Created:</span>
                                            <span class="font-medium text-gray-900 ml-2">{{ $club->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col space-y-2 ml-4">
                                    <button 
                                        @click="approveClub({{ $club->id }})"
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                    <button 
                                        @click="rejectClub({{ $club->id }})"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No pending approvals</h3>
                    <p class="mt-1 text-sm text-gray-500">All clubs have been reviewed.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Recent Users</h2>
        </div>
        
        <div class="p-6">
            @if($recent_users->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recent_users as $user)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500">Joined {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No recent users</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection