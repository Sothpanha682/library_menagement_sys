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
        <button @click="showLoanModal = true" type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Issue New Loan
        </button>
    </div>

    <!-- Issue Loan Modal -->
    <div x-show="showLoanModal" style="display: none;" class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40">
        <div @click.away="showLoanModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Issue New Loan</h3>

            <div>
                <template x-if="authError">
                    <div class="mb-4 px-3 py-2 rounded text-sm bg-red-50 text-red-700" x-text="authError"></div>
                </template>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Select Book</label>
                    <select x-model="loanForm.book_id" class="block w-full rounded-lg border-slate-300 px-3 py-2">
                        <option value="">Select a book</option>
                        <template x-for="b in books" :key="b.id">
                            <option :value="b.id" x-text="b.title"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Select Member</label>
                    <select x-model="loanForm.member_id" class="block w-full rounded-lg border-slate-300 px-3 py-2">
                        <option value="">Select a member</option>
                        <template x-for="m in members" :key="m.id">
                            <option :value="m.id" x-text="m.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" @click="showLoanModal = false" class="bg-white py-2 px-4 border border-slate-300 rounded-lg text-sm">Cancel</button>
                <button type="button" @click.prevent="(async () => { if (!loanForm.book_id || !loanForm.member_id) { authError = 'Select both book and member'; return; } await issueLoan(loanForm.book_id, loanForm.member_id); loanForm.book_id = null; loanForm.member_id = null; showLoanModal = false; })()" class="bg-indigo-600 text-white py-2 px-4 rounded-lg text-sm">Issue Loan</button>
            </div>
        </div>
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
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-10 flex-shrink-0 bg-indigo-100 rounded-lg overflow-hidden flex items-center justify-center text-indigo-600 font-bold">
                                        <img x-show="loan.bookImage" :src="loan.bookImage" alt="Cover" class="h-full w-full object-cover">
                                        <span x-show="!loan.bookImage" x-text="loan.book.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900" x-text="loan.book"></div>
                                        <div class="text-sm text-slate-500" x-text="loan.member"></div>
                                    </div>
                                </div>
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
                                <button @click="returnBook(loan.id)" x-show="loan.status === 'Active'" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md text-xs transition-colors">Mark Returned</button>
                                <span x-show="loan.status !== 'Active'" class="text-slate-500 text-xs">Returned</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
