<!-- MEMBERS FORM VIEW -->
<div x-show="memberView === 'form'" style="display: none;" x-transition.opacity.duration.300ms class="max-w-4xl mx-auto">
    <button @click="memberView = 'list'" class="mb-6 flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors font-medium">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Members
    </button>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg leading-6 font-semibold text-slate-900" x-text="memberToEdit ? 'Update Member Profile' : 'Register New Member'"></h3>
        </div>
        <form @submit.prevent="handleMemberSubmit()" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" x-model="memberForm.name" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" x-model="memberForm.email" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                    <input type="text" x-model="memberForm.phone" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select x-model="memberForm.status" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 pt-5 border-t border-slate-200 flex items-center justify-between gap-3">
                <template x-if="memberToEdit">
                    <button type="button" @click="confirmMemberDelete(memberToEdit.id)" class="bg-red-50 py-2 px-4 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 hover:bg-red-100 transition-colors">Delete Member</button>
                </template>
                <div class="flex items-center gap-3">
                    <button type="button" @click="memberView = 'list'" class="bg-white py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" :disabled="isLoading" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors disabled:opacity-50" x-text="memberToEdit ? 'Save Changes' : 'Register Member'"></button>
                </div>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <template x-if="showMemberDeleteModal">
        <div class="fixed inset-0 z-50 overflow-y-auto" @click.self="showMemberDeleteModal = false">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-11a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-semibold text-slate-900">Delete Member</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500">Are you sure you want to delete this member? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="deleteMember()" :disabled="isLoading" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">Delete</button>
                        <button type="button" @click="showMemberDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
