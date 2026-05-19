<!-- ========================================== -->
<!-- LOANS TAB -->
<!-- ========================================== -->
<div x-show="activeTab === 'loans'" style="display: none;" x-transition.opacity.duration.300ms class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3">
            <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-sm border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-slate-700">
                <option>All Loans</option>
                <option>Active</option>
                <option>Overdue</option>
            </select>
        </div>
        <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Issue New Loan
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Book Details</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Borrow Date</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Due Date</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <template x-for="loan in loans" :key="loan.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900" x-text="loan.book"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-600" x-text="loan.member"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500" x-text="loan.borrowDate"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="loan.status === 'Overdue' ? 'text-red-600' : 'text-slate-600'" x-text="loan.dueDate"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      :class="loan.status === 'Active' ? 'bg-indigo-100 text-indigo-800' : 'bg-red-100 text-red-800'">
                                    <span x-text="loan.status"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md text-xs transition-colors">Mark Returned</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
