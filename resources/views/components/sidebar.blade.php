<!-- Sidebar Navigation -->
<aside class="w-64 bg-white border-r border-slate-200 flex flex-col z-20 fixed md:static inset-y-0 left-0 md:inset-auto transition-transform duration-300 ease-in-out" 
       :class="{ '-translate-x-full md:translate-x-0': !sidebarOpen, 'translate-x-0': sidebarOpen }"
       @click.away="sidebarOpen = false">
    <div class="h-16 flex items-center px-6 border-b border-slate-200">
        <svg class="w-8 h-8 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        <span class="text-xl font-bold text-slate-800">LibSys</span>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="#" @click.prevent="activeTab = 'books'; sidebarOpen = false" :class="activeTab === 'books' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Books Inventory
        </a>
        <a href="#" @click.prevent="activeTab = 'members'; sidebarOpen = false" :class="activeTab === 'members' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Members
        </a>
        <a href="#" @click.prevent="activeTab = 'loans'; sidebarOpen = false" :class="activeTab === 'loans' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Loans & Returns
        </a>
        <a href="#" @click.prevent="activeTab = 'reports'; sidebarOpen = false" :class="activeTab === 'reports' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Reports
        </a>
    </nav>
    <div class="p-4 border-t border-slate-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin" class="w-9 h-9 rounded-full">
                <div>
                    <p class="text-sm font-medium text-slate-800">Admin User</p>
                    <p class="text-xs text-slate-500">Librarian</p>
                </div>
            </div>
            <button @click="logout()" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Sign out">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </div>
    </div>
</aside>
