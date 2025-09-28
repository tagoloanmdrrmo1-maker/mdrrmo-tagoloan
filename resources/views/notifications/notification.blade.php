@extends('layouts.app')

@section('title', 'Notifications')

@section('page_heading', 'Notifications')

@section('content')
<div class="bg-white rounded-lg shadow-sm border">
    <!-- Header -->
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800">All Notifications</h2>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="divide-y divide-gray-200">
        @forelse($notifications as $notification)
            <div class="p-4 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'opacity-75' : '' }}">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-10 h-10 {{ $notification->colorClass }} rounded-full flex items-center justify-center">
                        <i class="{{ $notification->icon }}"></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-medium text-gray-900">
                            {{ $notification->title }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $notification->message }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $notification->formattedTime }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        @unless($notification->is_read)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                    Mark as read
                                </button>
                            </form>
                        @endunless
                        
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this notification?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-bell-slash text-4xl mb-3"></i>
                <p class="text-lg">No notifications found</p>
                <p class="text-sm mt-1">You'll see your notifications here when they arrive</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="p-4 border-t">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection