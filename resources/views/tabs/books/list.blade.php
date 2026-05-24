<!-- ========================================== -->
<!-- READ VIEW (Data Table) -->
<!-- ========================================== -->
<div x-show="view === 'list'" x-transition.opacity.duration.300ms class="space-y-6">
    
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3">
            <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-sm border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-slate-700">
                <option>All Categories</option>
                <option>Fiction</option>
                <option>Programming</option>
                <option>Sci-Fi</option>
            </select>
            <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-sm border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-slate-700">
                <option>Status: Any</option>
                <option>Available</option>
                <option>Borrowed</option>
            </select>
        </div>
        
        <button @click="openCreate()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add New Book
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Book Info</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Copies</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <!-- LOOP STARTS HERE (Alpine.js Template) -->
                    <template x-for="book in books" :key="book.id">
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-10 flex-shrink-0 bg-indigo-100 rounded-lg overflow-hidden flex items-center justify-center text-indigo-600 font-bold">
                                        <img x-show="book.image" :src="book.image" alt="Book cover" class="h-full w-full object-cover">
                                        <span x-show="!book.image" x-text="book.title.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900" x-text="book.title"></div>
                                        <div class="text-sm text-slate-500" x-text="book.author"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800" x-text="book.category"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      :class="book.status === 'Available' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'">
                                    <svg class="w-3 h-3 mr-1" :class="book.status === 'Available' ? 'text-green-500' : 'text-amber-500'" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    <span x-text="book.status"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                <span x-text="book.copies"></span> available
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Button -->
                                    <button @click="openView(book)" class="p-2 text-slate-300 hover:text-slate-400 rounded-lg transition-colors tooltip-trigger" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <!-- Edit Button -->
                                    <button @click="openEdit(book)" class="p-2 text-slate-300 hover:text-slate-400 rounded-lg transition-colors tooltip-trigger" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button @click="confirmDelete(book.id)" class="p-2 text-slate-300 hover:text-slate-400 rounded-lg transition-colors tooltip-trigger" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <!-- LOOP ENDS -->
                </tbody>
            </table>
        </div>
        <!-- Pagination (Placeholder) -->
        <div class="bg-white px-4 py-3 border-t border-slate-200 sm:px-6 flex items-center justify-between">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-700">
                        Showing <span class="font-medium" x-text="(totalBooks === 0) ? 0 : ((currentPage - 1) * perPage + 1)"></span>
                        to <span class="font-medium" x-text="Math.min(totalBooks, currentPage * perPage)"></span>
                        of <span class="font-medium" x-text="totalBooks"></span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button type="button" @click.prevent="prevPage()" :disabled="currentPage <= 1" :class="currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : ''" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        </button>

                        <template x-for="p in pages" :key="p">
                            <button type="button" @click.prevent="gotoPage(p)" :class="p === currentPage ? 'relative inline-flex items-center px-4 py-2 border border-slate-300 bg-indigo-50 text-sm font-medium text-indigo-600' : 'relative inline-flex items-center px-4 py-2 border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50'" x-text="p"></button>
                        </template>

                        <button type="button" @click.prevent="nextPage()" :disabled="currentPage >= lastPage" :class="currentPage >= lastPage ? 'opacity-50 cursor-not-allowed' : ''" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
