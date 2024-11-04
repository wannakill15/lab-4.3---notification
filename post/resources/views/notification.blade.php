<x-app-layout>
    <x-slot name="header">
        <div class="bg-blue-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">
                {{ __(key: 'Notifications') }}
            </h2>
        </div>
    </x-slot>

    <br>


    <div ng-app="socialApp" ng-controller="NotificationController" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="notification-wrapper">
            <div class="notification toggle">
                <i class="fas fa-bell"></i>
                <span class="badge" ng-if="unreadCount > 0">@{{ unreadCount }}</span>
            </div>

            <div class="notification-dropdown">
                <div class="notification header">
                    <h3>{{ __('Notifications') }}</h3>
                    <button ng-click="markAllAsRead()" ng-if="unreadCount > 0" class="btn-sm btn-primary">
                        {{ __('Mark all as read') }}
                    </button>
                </div>

                <div class="notification list">
                    <div ng-repeat="notification in notifications" class="notification-item" ng-class="{'unread': !notification.is_read}" ng-click="markAsRead(notification.id)">
                        <div class="notification-content">
                            <p>@{{ notification.message }}</p>
                            <small>@{{ notification.created_at | date:'medium' }}</small>
                        </div>
                    </div>

                    <div ng-if="notifications.length === 0" class="no-notification">
                        <p>{{ __('No notifications') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Set up CSRF token for AJAX requests
    $http.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>
</x-app-layout>