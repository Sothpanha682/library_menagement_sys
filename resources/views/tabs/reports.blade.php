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
                <p class="text-2xl font-bold text-slate-800" x-text="reportSummary.totalBooks.toLocaleString()"></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Active Members</p>
                <p class="text-2xl font-bold text-slate-800" x-text="reportSummary.activeMembers.toLocaleString()"></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Active Loans</p>
                <p class="text-2xl font-bold text-slate-800" x-text="reportSummary.activeLoans.toLocaleString()"></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
            <div class="p-3 rounded-xl bg-red-50 text-red-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Overdue Books</p>
                <p class="text-2xl font-bold text-slate-800" x-text="reportSummary.overdueBooks.toLocaleString()"></p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Loans Overview (Last 6 Months)</h3>
                    <p class="text-sm text-slate-500 mt-1">Updated <span x-text="reportLastUpdated || 'moments ago'"></span></p>
                </div>
                <span class="text-xs font-medium rounded-full bg-slate-100 text-slate-600 px-3 py-1">Live data</span>
            </div>

            <div class="h-64 flex items-end justify-between gap-4 border-b border-l border-slate-200 pb-2 pl-2 relative">
                <template x-for="month in reportLoanTrend" :key="month.key">
                    <div class="flex-1 flex flex-col items-center justify-end h-full">
                        <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm min-h-[6px]" :style="`height: ${Math.max(8, (month.count / Math.max(...reportLoanTrend.map(item => item.count), 1)) * 100)}%`"></div>
                    </div>
                </template>
                <div x-show="!reportLoanTrend.length" class="absolute inset-0 flex items-center justify-center text-sm text-slate-500">
                    No loan activity yet.
                </div>
            </div>

            <div class="flex justify-between mt-2 text-sm font-medium text-slate-500 px-2">
                <template x-for="month in reportLoanTrend" :key="month.key + '-label'">
                    <span x-text="month.label"></span>
                </template>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">Recent Activity</h3>
            <div class="space-y-5">
                <template x-for="activity in reportRecentActivity" :key="activity.type + activity.sortTime">
                    <div class="flex items-start gap-4">
                        <div class="w-2.5 h-2.5 mt-1.5 rounded-full shrink-0" :class="activity.dotClass"></div>
                        <div>
                            <p class="text-sm text-slate-800" x-text="activity.title"></p>
                            <p class="text-xs text-slate-500 mt-0.5" x-text="activity.subtitle"></p>
                        </div>
                    </div>
                </template>
                <p x-show="!reportRecentActivity.length" class="text-sm text-slate-500">No recent activity yet.</p>
            </div>
            <button class="w-full mt-6 py-2 text-sm text-indigo-600 font-medium bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">View All Activity</button>
        </div>
    </div>
</div>
