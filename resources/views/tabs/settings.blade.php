<!-- Settings Tab -->
<div x-show="activeTab === 'settings'" class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-4xl">
        <!-- Settings Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Settings</h1>
            <p class="text-slate-600 mt-2">Manage your profile and account settings</p>
        </div>

        <!-- Success/Error Messages -->
        <div x-show="settingsSuccess" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm" x-transition>
            <p x-text="settingsSuccess"></p>
        </div>
        <div x-show="settingsError" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-transition>
            <p x-text="settingsError"></p>
        </div>

        <!-- Settings Sections -->
        <div class="space-y-6">

            <!-- Profile Section -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-900">Profile Information</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Profile Image -->
                        <div class="flex flex-col items-center">
                            <div class="relative mb-4">
                                <img 
                                    :src="currentUser.profile_image || '/images/avatar.png'"
                                    :alt="currentUser.name"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100">
                                <label class="absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full cursor-pointer hover:bg-indigo-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <input type="file" accept="image/*" class="hidden" @change="handleProfileImageUpload">
                                </label>
                            </div>
                            <p class="text-xs text-slate-500 text-center">Click to upload a new photo</p>
                        </div>

                        <!-- Profile Form -->
                        <div class="flex-1">
                            <div class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label for="profile-name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                                    <input 
                                        id="profile-name"
                                        type="text" 
                                        x-model="profileForm.name"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        placeholder="Enter your full name">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="profile-email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                                    <input 
                                        id="profile-email"
                                        type="email" 
                                        x-model="profileForm.email"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        placeholder="Enter your email">
                                </div>

                                <!-- Save Button -->
                                <button 
                                    @click="updateProfile()"
                                    :disabled="isLoading"
                                    class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!isLoading">Save Changes</span>
                                    <span x-show="isLoading">Saving...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-900">Change Password</h2>
                </div>
                <div class="p-6">
                    <div class="flex-1">
                        <div class="space-y-4">
                            <!-- Current Password -->
                            <div>
                                <label for="current-password" class="block text-sm font-medium text-slate-700 mb-1">Current Password</label>
                                <input 
                                    id="current-password"
                                    type="password" 
                                    x-model="passwordForm.current_password"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Enter your current password">
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="new-password" class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                                <input 
                                    id="new-password"
                                    type="password" 
                                    x-model="passwordForm.new_password"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Enter new password">
                                <p class="text-xs text-slate-500 mt-1">Minimum 8 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                                <input 
                                    id="confirm-password"
                                    type="password" 
                                    x-model="passwordForm.password_confirmation"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Confirm new password">
                            </div>

                            <!-- Change Password Button -->
                            <button 
                                @click="changePassword()"
                                :disabled="isLoading"
                                class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isLoading">Change Password</span>
                                <span x-show="isLoading">Updating...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo Upload Section -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-900">Organization Logo</h2>
                </div>
                <div class="p-6">
                    <div class="flex-1">
                        <!-- Logo Name Input -->
                        <div class="mb-4">
                            <label for="logo-name" class="block text-sm font-medium text-slate-700 mb-1">Logo Name</label>
                            <input 
                                id="logo-name"
                                type="text" 
                                x-model="logoForm.name"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="e.g., LibSys, My Library">
                            <p class="text-xs text-slate-500 mt-1">Display name for your library system</p>
                        </div>

                        <!-- Current Logo Preview (if exists) -->
                        <div x-show="systemLogo.path" class="mb-4">
                            <p class="text-sm font-medium text-slate-700 mb-2">Current Logo</p>
                            <div class="flex items-center justify-center border border-slate-300 rounded-lg p-4 bg-slate-50">
                                <img :src="systemLogo.path" :alt="systemLogo.name" class="h-20 w-auto max-w-full">
                            </div>
                            <p class="text-xs text-slate-500 mt-2 text-center" x-text="'Logo: ' + systemLogo.name"></p>
                        </div>

                        <!-- Upload Area -->
                        <div class="mb-4">
                            <p class="text-sm text-slate-600 mb-4">Upload a logo for your library system. This will be displayed in the sidebar and login page.</p>
                            <div class="flex items-center justify-center border-2 border-dashed border-slate-300 rounded-lg p-6 bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors" @click="document.getElementById('logo-upload').click()">
                                <div class="text-center">
                                    <!-- Show preview if new file is selected -->
                                    <div x-show="logoPreview">
                                        <img :src="logoPreview" alt="Logo preview" class="h-20 w-auto mx-auto mb-2">
                                        <p class="text-sm font-medium text-slate-900">New Logo Selected</p>
                                        <p class="text-xs text-slate-500">Click to change</p>
                                    </div>
                                    <!-- Show upload prompt if no preview -->
                                    <div x-show="!logoPreview">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="mt-2 text-sm font-medium text-slate-900">Click to upload logo</p>
                                        <p class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
                                    </div>
                                </div>
                                <input 
                                    id="logo-upload"
                                    type="file" 
                                    accept="image/*"
                                    class="hidden"
                                    @change="handleLogoUpload">
                            </div>
                        </div>

                        <!-- Upload Button (only show when preview exists) -->
                        <div x-show="logoPreview" class="mt-4">
                            <button 
                                @click="uploadLogo()"
                                :disabled="isLoading"
                                class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isLoading">Upload Logo</span>
                                <span x-show="isLoading">Uploading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
