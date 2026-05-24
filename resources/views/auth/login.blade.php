<!-- ========================================== -->
<!-- AUTHENTICATION LAYOUT -->
<!-- ========================================== -->
<div x-show="!isAuthenticated" style="display: flex; min-height: 100vh; flex-direction: column; justify-content: center; padding-top: 3rem; padding-bottom: 3rem; padding-left: 1.5rem; padding-right: 1.5rem; background-color: #f8fafc; position: relative; overflow: hidden;" class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-50 relative overflow-hidden">
    <!-- Background Decoration -->
    <div style="position: absolute; inset: 0; z-index: -1; background: radial-gradient(ellipse at top, rgb(224, 231, 255), rgb(248, 250, 252));" class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-100 via-slate-50 to-slate-50"></div>
    
    <div style="margin-left: auto; margin-right: auto; width: 100%; max-width: 28rem; margin-bottom: 1.5rem; text-align: center;" class="sm:mx-auto sm:w-full sm:max-w-md mb-6 text-center">
        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 1rem;" class="flex justify-center items-center mb-4">
            <img 
                x-show="systemLogo.path"
                :src="systemLogo.path" 
                :alt="systemLogo.name"
                style="width: 3rem; height: 3rem; margin-right: 0.5rem; object-fit: contain;"
                class="w-12 h-12 mr-2 object-contain">
            <svg 
                x-show="!systemLogo.path"
                style="width: 3rem; height: 3rem; color: #4f46e5; margin-right: 0.5rem;" class="w-12 h-12 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span style="font-size: 2.25rem; font-weight: 800; color: #1e293b; letter-spacing: -0.02em;" class="text-4xl font-extrabold text-slate-900 tracking-tight" x-text="systemLogo.name || 'LibSys'"></span>
        </div>
        <h2 style="font-size: 1.875rem; font-weight: 700; color: #1e293b;" class="text-2xl font-bold text-slate-800" x-text="authView === 'login' ? 'Sign in to your account' : 'Reset your password'"></h2>
        <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #475569;" class="mt-2 text-sm text-slate-600" x-show="authView === 'login'">
            Use your library account to continue.
        </p>
    </div>

    <div style="margin-left: auto; margin-right: auto; width: 100%; max-width: 28rem;" class="sm:mx-auto sm:w-full sm:max-w-md">
        <div style="background-color: white; padding-top: 2rem; padding-bottom: 2rem; padding-left: 1rem; padding-right: 1rem; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.05); border-radius: 1rem; border: 1px solid #e2e8f0; position: relative;" class="bg-white py-8 px-4 shadow-xl shadow-slate-200/50 sm:rounded-2xl sm:px-10 border border-slate-100 relative">
            
            <!-- Alert Messages -->
            <div x-show="authError" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; color: #dc2626; font-size: 0.875rem;" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm" x-transition>
                <p x-text="authError"></p>
            </div>
            
            <div x-show="authSuccess" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.5rem; color: #16a34a; font-size: 0.875rem;" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm" x-transition>
                <p x-text="authSuccess"></p>
            </div>
            
            <!-- Login Form -->
            <form x-show="authView === 'login'" @submit.prevent="handleLogin()" style="display: block; space-y: 1.5rem;" class="space-y-6" x-transition.opacity.duration.300ms>
                <div style="display: block; margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151;" class="block text-sm font-medium text-slate-700">Email address</label>
                    <div style="margin-top: 0.25rem;">
                        <input id="email" name="email" type="email" autocomplete="email" required style="appearance: none; display: block; width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #374151; font-size: 0.875rem;" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="admin@libsys.com" x-model="loginForm.email">
                    </div>
                </div>

                <div style="display: block; margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151;" class="block text-sm font-medium text-slate-700">Password</label>
                    <div style="margin-top: 0.25rem;">
                        <input id="password" name="password" type="password" autocomplete="current-password" required style="appearance: none; display: block; width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #374151; font-size: 0.875rem;" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="••••••••" x-model="loginForm.password">
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;" class="flex items-center justify-between">
                    <div style="display: flex; align-items: center;" class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" style="width: 1rem; height: 1rem; color: #4f46e5; border-color: #cbd5e1; border-radius: 0.25rem; cursor: pointer;" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer" x-model="loginForm.rememberMe">
                        <label for="remember-me" style="margin-left: 0.5rem; display: block; font-size: 0.875rem; color: #1f2937; cursor: pointer;" class="ml-2 block text-sm text-slate-900 cursor-pointer">
                            Remember me
                        </label>
                    </div>

                </div>

                <div style="display: block; margin-bottom: 1.5rem;">
                    <button type="submit" :disabled="isLoading" style="width: 100%; display: flex; justify-content: center; padding-top: 0.625rem; padding-bottom: 0.625rem; padding-left: 1rem; padding-right: 1rem; border: none; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); font-size: 0.875rem; font-weight: 500; color: white; background-color: #4f46e5; cursor: pointer; transition: all 0.2s;" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Sign in</span>
                        <span x-show="isLoading">Signing in...</span>
                    </button>
                </div>
            </form>

            <!-- Forgot Password Form -->
            <form x-show="authView === 'forgot'" @submit.prevent="authView = 'login'" style="display: none;" class="space-y-6" x-transition.opacity.duration.300ms>
                <div style="background-color: #eef2ff; color: #4338ca; padding: 1rem; border-radius: 0.5rem; font-size: 0.875rem;" class="bg-indigo-50 text-indigo-700 p-4 rounded-lg text-sm">
                    Enter your email address and we will send you a link to reset your password.
                </div>
                
                <div style="display: block; margin-bottom: 1.5rem;">
                    <label for="reset-email" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151;" class="block text-sm font-medium text-slate-700">Email address</label>
                    <div style="margin-top: 0.25rem;">
                        <input id="reset-email" name="email" type="email" required style="appearance: none; display: block; width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #374151; font-size: 0.875rem;" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="your-email@example.com">
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <button type="submit" style="width: 100%; display: flex; justify-content: center; padding-top: 0.625rem; padding-bottom: 0.625rem; padding-left: 1rem; padding-right: 1rem; border: none; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); font-size: 0.875rem; font-weight: 500; color: white; background-color: #4f46e5; cursor: pointer; transition: all 0.2s;" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Send Reset Link
                    </button>
                    <button type="button" @click="authView = 'login'" style="width: 100%; display: flex; justify-content: center; padding-top: 0.625rem; padding-bottom: 0.625rem; padding-left: 1rem; padding-right: 1rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); font-size: 0.875rem; font-weight: 500; color: #374151; background-color: white; cursor: pointer; transition: all 0.2s;" class="w-full flex justify-center py-2.5 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Back to Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
