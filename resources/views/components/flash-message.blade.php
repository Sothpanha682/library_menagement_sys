<!-- FLASH MESSAGE (Example placeholder for backend session success) -->
@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-50 border-l-4 border-green-500 flex items-start shadow-sm">
        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h3 class="text-sm font-medium text-green-800">Success</h3>
            <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
        </div>
    </div>
@endif
