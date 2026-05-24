<!-- ========================================== -->
<!-- CREATE / UPDATE VIEW (Form) -->
<!-- ========================================== -->
<div x-show="view === 'form'" style="display: none;" x-transition.opacity.duration.300ms class="max-w-4xl mx-auto">
    
    <button @click="view = 'list'" class="mb-6 flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors font-medium">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Inventory
    </button>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg leading-6 font-semibold text-slate-900" x-text="bookToEdit ? 'Update Book Information' : 'Register New Book'"></h3>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Ensure all details are accurate for cataloging.</p>
        </div>

        <!-- Note: In a real Laravel app, form action would be dynamically set to route('books.store') or route('books.update', $book->id) -->
        <form action="javascript:void(0)" method="POST" @submit.prevent="handleBookSubmit()" class="p-6">
            <!-- CSRF Token and Method Directives would go here -->
            <!-- @csrf -->
            <!-- @if(isset($book)) @method('PUT') @endif -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Book Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" x-model="bookForm.title" required placeholder="e.g., The Great Gatsby">
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-slate-700 mb-1">Author / Writer <span class="text-red-500">*</span></label>
                    <input type="text" name="author" id="author" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" x-model="bookForm.author" required placeholder="e.g., F. Scott Fitzgerald">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white" x-model="bookForm.category">
                        <option value="">Select a category</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Programming">Programming</option>
                        <option value="Sci-Fi">Science Fiction</option>
                        <option value="History">History</option>
                    </select>
                </div>

                <!-- Total Copies -->
                <div>
                    <label for="copies" class="block text-sm font-medium text-slate-700 mb-1">Total Copies <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="copies" min="1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" x-model="bookForm.quantity" @change="syncAvailableCopies()" required>
                </div>

                <!-- Available Copies -->
                <div>
                    <label for="available_quantity" class="block text-sm font-medium text-slate-700 mb-1">Available Copies <span class="text-red-500">*</span></label>
                    <input type="number" name="available_quantity" id="available_quantity" class="block w-full rounded-lg border-slate-300 shadow-sm bg-slate-100 sm:text-sm px-4 py-2 border text-slate-600" x-model="bookForm.available_quantity" readonly>
                    <p class="mt-1 text-xs text-slate-500">Auto-calculated based on total copies and active loans</p>
                </div>

                <!-- Published Year -->
                <div>
                    <label for="published_year" class="block text-sm font-medium text-slate-700 mb-1">Published Year <span class="text-red-500">*</span></label>
                    <input type="number" name="published_year" id="published_year" min="1900" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" x-model="bookForm.published_year" required>
                </div>

                <!-- Book Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-3">Book Cover Image</label>
                    
                    <!-- Image Preview -->
                    <div x-show="bookForm.image" class="mb-4">
                        <div class="relative inline-block">
                            <img :src="bookForm.image" alt="Book cover preview" class="h-40 w-auto rounded-lg shadow-md border border-slate-200">
                            <button type="button" @click="bookForm.image = ''" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- URL Input -->
                    <div class="mb-4">
                        <label for="image_url" class="block text-xs font-medium text-slate-600 mb-1">Image URL</label>
                        <input type="url" id="image_url" name="image_url" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" x-model="bookForm.image" placeholder="https://example.com/book-cover.jpg">
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label for="book_image" class="block text-xs font-medium text-slate-600 mb-1">Or Browse File</label>
                        <div class="flex items-center gap-2">
                            <input type="file" id="book_image" name="book_image" accept="image/*" @change="handleBookImageUpload" class="block flex-1 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <span class="text-xs text-slate-500">(Max 5MB)</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Synopsis / Description</label>
                    <textarea id="description" name="description" rows="4" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" x-model="bookForm.description" placeholder="Brief summary of the book..."></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
                <button type="button" @click="view = 'list'" class="bg-white py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Cancel
                </button>
                <button type="submit" :disabled="isLoading" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" x-text="isLoading ? 'Saving...' : (bookToEdit ? 'Save Changes' : 'Save New Book')">
                </button>
            </div>
        </form>
    </div>
</div>
