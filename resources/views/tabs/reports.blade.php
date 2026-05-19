<!-- ========================================== -->
<!-- REPORTS TAB -->
<!-- ========================================== -->
<div x-show="activeTab === 'reports'" style="display: none;" x-transition.opacity.duration.300ms class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Books</p>
                <p class="text-2xl font-bold text-slate-800">1,248</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Active Members</p>
                <p class="text-2xl font-bold text-slate-800">452</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Active Loans</p>
                <p class="text-2xl font-bold text-slate-800">89</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-red-50 text-red-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Overdue Books</p>
                <p class="text-2xl font-bold text-slate-800">12</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">Loans Overview (Last 6 Months)</h3>
            <!-- Simple CSS Bar Chart Placeholder -->
            <div class="h-64 flex items-end justify-between gap-4 border-b border-l border-slate-200 pb-2 pl-2 relative">
                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 40%"></div>
                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 65%"></div>
                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 45%"></div>
                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 80%"></div>
                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 55%"></div>
                <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 90%"></div>
            </div>
            <div class="flex justify-between mt-2 text-sm font-medium text-slate-500 px-2">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">Recent Activity</h3>
            <div class="space-y-5">
                <div class="flex items-start gap-4">
                    <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-green-500 shrink-0"></div>
                    <div>
                        <p class="text-sm text-slate-800"><strong>Alice Johnson</strong> returned <span class="text-indigo-600 cursor-pointer">The Great Gatsby</span></p>
                        <p class="text-xs text-slate-500 mt-0.5">2 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-amber-500 shrink-0"></div>
                    <div>
                        <p class="text-sm text-slate-800"><strong>Bob Smith</strong> borrowed <span class="text-indigo-600 cursor-pointer">Clean Code</span></p>
                        <p class="text-xs text-slate-500 mt-0.5">5 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-indigo-500 shrink-0"></div>
                    <div>
                        <p class="text-sm text-slate-800">New member <strong>Charlie Brown</strong> registered</p>
                        <p class="text-xs text-slate-500 mt-0.5">1 day ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-red-500 shrink-0"></div>
                    <div>
                        <p class="text-sm text-slate-800">Automated notice sent for <span class="text-indigo-600 cursor-pointer">1984</span> (Overdue)</p>
                        <p class="text-xs text-slate-500 mt-0.5">2 days ago</p>
                    </div>
                </div>
            </div>
            <button class="w-full mt-6 py-2 text-sm text-indigo-600 font-medium bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">View All Activity</button>
        </div>
    </div>
</div>
