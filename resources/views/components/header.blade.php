<!-- Top Header -->
<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 z-20">
    <div class="flex items-center gap-4">
        <!-- Mobile menu button -->
        <button type="button" @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:text-slate-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <h1 class="text-xl font-semibold text-slate-800" x-text="getHeaderTitle()">Books Inventory</h1>
    </div>
    
    <!-- Global Search -->
    <div class="hidden sm:flex items-center max-w-md w-full ml-8 relative" x-show="activeTab === 'books' || activeTab === 'members'">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow" placeholder="Search records...">
    </div>

    <!-- Header Actions -->
    <div class="flex items-center gap-4 relative">
        <!-- Notification Bell Icon -->
        <button @click="toggleNotificationPanel()" class="text-slate-500 hover:text-indigo-600 relative transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <!-- Notification Badge -->
            <template x-if="notificationCount > 0">
                <span class="absolute top-0 right-0 block h-5 w-5 rounded-full bg-red-500 ring-2 ring-white text-white text-xs font-bold flex items-center justify-center" x-text="notificationCount > 9 ? '9+' : notificationCount"></span>
            </template>
        </button>

        <!-- Notification Panel -->
        <div x-show="showNotificationPanel" @click.away="showNotificationPanel = false" class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-2xl border border-slate-200 z-50 max-h-96 overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-4 py-3 flex items-center justify-between">
                <h3 class="font-semibold text-sm">Notifications</h3>
                <button @click="clearAllNotifications()" class="text-indigo-200 hover:text-white text-xs">Clear All</button>
            </div>

            <!-- Notifications List -->
            <div class="overflow-y-auto flex-1">
                <template x-if="notifications.length === 0">
                    <div class="p-8 text-center text-slate-400 text-sm">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        No notifications yet
                    </div>
                </template>

                <template x-for="notification in notifications" :key="notification.id">
                    <div :class="[
                        'px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition-colors flex items-start gap-3 group',
                        {
                            'bg-green-50': notification.type === 'success',
                            'bg-red-50': notification.type === 'error',
                            'bg-yellow-50': notification.type === 'warning',
                            'bg-blue-50': notification.type === 'info'
                        }
                    ]">
                        <!-- Icon based on type -->
                        <div :class="[
                            'w-2 h-2 rounded-full mt-1.5 flex-shrink-0',
                            {
                                'bg-green-500': notification.type === 'success',
                                'bg-red-500': notification.type === 'error',
                                'bg-yellow-500': notification.type === 'warning',
                                'bg-blue-500': notification.type === 'info'
                            }
                        ]"></div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800" x-text="notification.message"></p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-slate-500" x-text="notification.timestamp"></p>
                                <template x-if="notification.action">
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded" x-text="notification.action"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <button @click="removeNotification(notification.id)" class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-slate-600 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</header>

