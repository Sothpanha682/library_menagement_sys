<!-- ========================================== -->
<!-- VIEW DETAIL (Read-only) -->
<!-- ========================================== -->
<div x-show="view === 'view'" style="display: none;" x-transition.opacity.duration.300ms class="max-w-4xl mx-auto">
    
    <button @click="view = 'list'" class="mb-6 flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors font-medium">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Inventory
    </button>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg leading-6 font-semibold text-slate-900">Book Details</h3>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">View complete information about this book.</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Book Image -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Book Cover</label>
                    <div x-show="bookForm.image" class="bg-slate-50 rounded-lg border border-slate-300 overflow-hidden">
                        <img :src="bookForm.image" alt="Book cover" class="w-full h-auto object-cover">
                    </div>
                    <div x-show="!bookForm.image" class="bg-slate-50 rounded-lg border border-slate-300 border-dashed flex items-center justify-center h-64">
                        <p class="text-slate-500 text-sm">No image available</p>
                    </div>
                </div>

                <!-- Book Details -->
                <div class="md:col-span-2">
                
                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                    <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700" x-text="bookForm.title"></div>
                </div>

                <!-- Author -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Author</label>
                    <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700" x-text="bookForm.author"></div>
                </div>

                <!-- Published Year -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Published Year</label>
                    <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700" x-text="bookForm.published_year"></div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                    <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700" x-text="bookForm.category"></div>
                </div>

                <!-- Total Copies -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Total Copies</label>
                    <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700" x-text="bookForm.quantity"></div>
                </div>

                <!-- Available Copies -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Available Copies</label>
                    <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700" x-text="bookForm.available_quantity"></div>
                </div>

                </div>
            </div>

            <!-- Description -->
            <div class="mt-6 pt-6 border-t border-slate-200">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <div class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 min-h-[120px]" x-text="bookForm.description || 'No description provided'"></div>
            </div>

      
        </div>
    </div>
</div>
