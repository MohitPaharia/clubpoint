{{-- resources/views/club/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', $club->name . ' - ClubHub')

@section('content')
    <div x-data="{
        activeTab: 'overview',
        newMessage: '',
        messages: [],
        isOwner: {{ auth()->id() === $club->owner_id ? 'true' : 'false' }},
        isMember: {{ $club->members()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }},
        hasRequestedJoin: {{ $club->joinRequests()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }},
        pollingInterval: null,
        isPolling: false,

        startPolling() {
            if (this.pollingInterval) return;
            this.isPolling = true;
            this.pollingInterval = setInterval(() => {
                this.fetchNewMessages();
            }, 5000); // Poll every 5 seconds
        },

        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
                this.isPolling = false;
            }
        },

        async fetchNewMessages() {
            try {
                const lastMessageId = this.messages.length > 0 
                    ? this.messages[this.messages.length - 1].id 
                    : 0;
                
                const res = await fetch(`/clubs/{{ $club->id }}/chats/poll?after=${lastMessageId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await res.json();
                
                if (data.success && data.messages.length > 0) {
                    this.messages.push(...data.messages);
                    this.$nextTick(() => {
                        this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
                    });
                }
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        },

        async sendJoinRequest() {
            try {
                const res = await fetch('/clubs/{{ $club->id }}/join/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.hasRequestedJoin = true;
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error sending join request');
            }
        },

        async acceptJoinRequest(userId) {
            try {
                const res = await fetch(`/clubs/{{ $club->id }}/join/${userId}/accept`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById(`request-${userId}`).remove();
                    alert(data.message);
                    window.location.reload();
                }
            } catch (error) {
                alert('Error accepting request');
            }
        },

        async rejectJoinRequest(userId) {
            try {
                const res = await fetch(`/clubs/{{ $club->id }}/join/${userId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById(`request-${userId}`).remove();
                    alert(data.message);
                }
            } catch (error) {
                alert('Error rejecting request');
            }
        },

        async removeMember(userId) {
            if (!confirm('Are you sure you want to remove this member from the club?')) return;
            try {
                const res = await fetch(`/clubs/{{ $club->id }}/members/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById(`member-${userId}`).remove();
                    alert(data.message);
                }
            } catch (error) {
                alert('Error removing member');
            }
        },

        async leaveClub() {
            if (!confirm('Are you sure you want to leave this club?')) return;
            try {
                const res = await fetch('/clubs/{{ $club->id }}/members/{{ auth()->id() }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    window.location.href = '/clubs';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error leaving club');
            }
        },

        async blockUser(userId) {
            if (!confirm('Block this user from the club?')) return;
            try {
                const res = await fetch(`/clubs/{{ $club->id }}/block/${userId}`, {
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
                }
            } catch (error) {
                alert('Error blocking user');
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim()) return;

            try {
                const res = await fetch('/clubs/{{ $club->id }}/chats', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ message: this.newMessage })
                });
                const data = await res.json();
                if (data.success) {
                    this.messages.push(data.message);
                    this.newMessage = '';
                }
            } catch (error) {
                alert('Error sending message');
            }
        }
    }" class="space-y-6">

        <!-- Club Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-40 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
            <div class="px-6 pb-6 -mt-16">
                <div class="flex items-end justify-between">
                    <div>
                        <div
                            class="w-32 h-32 bg-white rounded-lg flex items-center justify-center text-indigo-600 font-bold text-4xl border-4 border-white shadow-lg">
                            {{ strtoupper(substr($club->name, 0, 2)) }}
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $club->name }}</h1>
                        <p class="text-gray-600 mt-2">{{ $club->description }}</p>
                    </div>

                    @if(auth()->id() === $club->owner_id)
                        <span class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold">Club Owner</span>
                    @elseif($club->members()->where('user_id', auth()->id())->exists())
                        <div class="flex items-center space-x-3">
                            <span class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">Member</span>
                            <button @click="leaveClub()"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Leave Club</span>
                            </button>
                        </div>
                    @else
                        <!-- Join Button for Non-Members -->
                        <div x-show="!hasRequestedJoin">
                            <button @click="sendJoinRequest()"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <span>Join Club</span>
                            </button>
                        </div>
                        <div x-show="hasRequestedJoin" x-cloak>
                            <span class="bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Request Pending</span>
                            </span>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Members</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $club->members->count() }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Events</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $club->events->count() }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Location</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $club->city->name ?? 'N/A' }},
                            {{ $club->state->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($club->members()->where('user_id', auth()->id())->exists() || auth()->id() === $club->owner_id)
            <!-- Tabs (Only visible to members and owner) -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                            Overview
                        </button>
                        <button @click="activeTab = 'members'"
                            :class="activeTab === 'members' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                            Members
                        </button>
                        <button @click="activeTab = 'events'"
                            :class="activeTab === 'events' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                            Events
                        </button>
                        <button @click="activeTab = 'chat'"
                            :class="activeTab === 'chat' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                            Chat
                        </button>
                        @if(auth()->id() === $club->owner_id)
                            <button @click="activeTab = 'requests'"
                                :class="activeTab === 'requests' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition relative">
                                Join Requests
                                @if($club->joinRequests->count() > 0)
                                    <span
                                        class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $club->joinRequests->count() }}
                                    </span>
                                @endif
                            </button>
                        @endif
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'" x-cloak>
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Club</h3>
                                <p class="text-gray-600">{{ $club->description }}</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Club Details</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-600 mb-1">Owner</dt>
                                        <dd class="text-sm text-gray-900">{{ $club->owner->first_name }}
                                            {{ $club->owner->last_name }}</dd>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-600 mb-1">Created</dt>
                                        <dd class="text-sm text-gray-900">{{ $club->created_at->format('F j, Y') }}</dd>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-600 mb-1">Location</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ auth()->user()->city?->name ?? 'N/A' }},
                                            {{ auth()->user()->state?->name ?? 'N/A' }},
                                            {{ auth()->user()->country?->name ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-600 mb-1">Status</dt>
                                        <dd class="text-sm">
                                            @if($club->approved)
                                                <span class="text-green-600 font-semibold">✓ Approved</span>
                                            @else
                                                <span class="text-yellow-600 font-semibold">⏳ Pending Approval</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Members Tab -->
                    <div x-show="activeTab === 'members'" x-cloak>
                        @if($club->members->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($club->members as $member)
                                    <div id="member-{{ $member->id }}" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $member->first_name }}
                                                        {{ $member->last_name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                            @if(auth()->id() === $club->owner_id && $member->id !== $club->owner_id)
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <div x-show="open" @click.away="open = false" x-cloak
                                                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                                        <button @click="removeMember({{ $member->id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                            Remove Member
                                                        </button>
                                                        <button @click="blockUser({{ $member->id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                            Block User
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <p class="text-gray-500">No members yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Events Tab -->
                    <div x-show="activeTab === 'events'" x-cloak>
                        @if(auth()->id() === $club->owner_id)
                            <div class="mb-6">
                                <a href="{{ route('club.event.create', $club) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                        </path>
                                    </svg>
                                    Create Event
                                </a>
                            </div>
                        @endif

                        @if($club->events->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($club->events as $event)
                                    <a href="{{ route('club.event.show', [$club, $event]) }}"
                                        class="block bg-gray-50 rounded-lg p-4 hover:shadow-md transition border border-gray-200">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $event->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ $event->description }}</p>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500">📍 {{ $event->address }}</span>
                                            <span
                                                class="font-semibold text-indigo-600">₹{{ number_format($event->ticket_cost, 2) }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No events yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Create your first event to get started.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Chat Tab -->
                    <div x-show="activeTab === 'chat'" x-cloak 
                        x-init="
                            messages = @js($club->chats->map(fn($chat) => [
                                'id' => $chat->id,
                                'user_id' => $chat->user_id,
                                'user_name' => $chat->user->first_name . ' ' . $chat->user->last_name,
                                'message' => $chat->message,
                                'created_at' => $chat->created_at
                            ])->toArray());

                            // Auto-scroll to bottom on load
                            $nextTick(() => {
                                $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight;
                            });

                            // Start polling when chat tab is opened
                            $watch('activeTab', value => {
                                if (value === 'chat') {
                                    startPolling();
                                } else {
                                    stopPolling();
                                }
                            });

                            // Start polling if chat tab is already active
                            if (activeTab === 'chat') {
                                startPolling();
                            }
                        "
                        @click="
                            $nextTick(() => {
                                $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight;
                            });
                        "
                    >
                        <div class="bg-gray-50 rounded-lg p-4 mb-4 max-h-96 overflow-y-auto relative" x-ref="chatContainer">
                            <!-- Polling Indicator -->
                            <div x-show="isPolling" class="absolute top-2 right-2 flex items-center space-x-2 bg-white px-3 py-1 rounded-full shadow-sm">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-gray-600">Live</span>
                            </div>

                            <template x-if="messages.length === 0">
                                <p class="text-center text-gray-500 py-8">No messages yet. Start the conversation!</p>
                            </template>

                            <template x-for="chat in messages" :key="chat.id">
                                <div class="mb-4" :class="chat.user_id === {{ auth()->id() }} ? 'text-right' : ''">
                                    <div class="inline-block rounded-lg px-4 py-2 max-w-md"
                                        :class="chat.user_id === {{ auth()->id() }} ? 'bg-indigo-600 text-white' : 'bg-white text-gray-900'">
                                        <p class="text-xs mb-1"
                                            :class="chat.user_id === {{ auth()->id() }} ? 'text-indigo-200' : 'text-gray-600'"
                                            x-text="chat.user_name">
                                        </p>
                                        <p class="text-sm" x-text="chat.message"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <form @submit.prevent="
                            if (newMessage.trim()) {
                                sendMessage().then(() => {
                                    $nextTick(() => {
                                        $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight;
                                    });
                                });
                            }
                        " class="flex space-x-2">
                            <input type="text" x-model="newMessage" placeholder="Type your message..."
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                                Send
                            </button>
                        </form>
                    </div>
                    
                    <!-- Join Requests Tab -->
                    @if(auth()->id() === $club->owner_id)
                        <div x-show="activeTab === 'requests'" x-cloak>
                            @if($club->joinRequests->count() > 0)
                                <div class="space-y-4">
                                    @foreach($club->joinRequests as $requester)
                                        <div id="request-{{ $requester->id }}"
                                            class="bg-gray-50 rounded-lg p-4 flex items-center justify-between border border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($requester->first_name, 0, 1) . substr($requester->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $requester->first_name }}
                                                        {{ $requester->last_name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $requester->email }}</p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button @click="acceptJoinRequest({{ $requester->id }})"
                                                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">
                                                    Accept
                                                </button>
                                                <button @click="rejectJoinRequest({{ $requester->id }})"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <p class="text-gray-500">No pending join requests</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Non-Member View -->
            <div class="bg-white rounded-xl shadow-md p-8">
                <div class="text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">Members Only Content</h3>
                    <p class="mt-2 text-gray-600">Join this club to access members area, view events, chat with members, and more!</p>
                    <div class="mt-6">
                        <div x-show="!hasRequestedJoin">
                            <button @click="sendJoinRequest()"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <span>Request to Join</span>
                            </button>
                        </div>
                        <div x-show="hasRequestedJoin" x-cloak>
                            <div class="inline-flex items-center px-6 py-3 bg-yellow-100 text-yellow-800 rounded-lg font-semibold space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Join Request Pending</span>
                            </div>
                            <p class="mt-3 text-sm text-gray-500">The club owner will review your request soon.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection