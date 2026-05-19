<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <!-- Include Tailwind CSS via CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Alpine.js for simple UI interactions (modals, dropdowns) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Custom scrollbar for better UX */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-screen w-full" x-data="{ 
              // Auth State
              isAuthenticated: false,
              authView: 'login', // 'login', 'register', 'forgot'
              login() { this.isAuthenticated = true; },
              logout() { this.isAuthenticated = false; this.authView = 'login'; },

              activeTab: 'books', // 'books', 'members', 'loans', 'reports'
              
              // Books State
              view: 'list', 
              showDeleteModal: false,
              bookToEdit: null,
              books: [
                  { id: 1, title: 'The Great Gatsby', author: 'F. Scott Fitzgerald', isbn: '978-0743273565', category: 'Fiction', status: 'Available', copies: 5 },
                  { id: 2, title: 'Clean Code', author: 'Robert C. Martin', isbn: '978-0132350884', category: 'Programming', status: 'Borrowed', copies: 0 },
                  { id: 3, title: '1984', author: 'George Orwell', isbn: '978-0451524935', category: 'Sci-Fi', status: 'Available', copies: 2 },
                  { id: 4, title: 'Design Patterns', author: 'Erich Gamma', isbn: '978-0201633610', category: 'Programming', status: 'Available', copies: 1 },
                  { id: 5, title: 'To Kill a Mockingbird', author: 'Harper Lee', isbn: '978-0060935467', category: 'Fiction', status: 'Available', copies: 3 },
              ],
              
              // Members State
              memberView: 'list',
              showMemberDeleteModal: false,
              memberToEdit: null,
              members: [
                  { id: 1, name: 'Alice Johnson', email: 'alice@example.com', phone: '(555) 123-4567', status: 'Active', joined: 'Oct 12, 2023' },
                  { id: 2, name: 'Bob Smith', email: 'bob.smith@example.com', phone: '(555) 987-6543', status: 'Inactive', joined: 'Nov 05, 2023' },
                  { id: 3, name: 'Charlie Brown', email: 'cbrown@example.com', phone: '(555) 456-7890', status: 'Active', joined: 'Jan 20, 2024' },
              ],

              // Loans State
              loans: [
                  { id: 1, book: 'Clean Code', member: 'Alice Johnson', borrowDate: '2024-05-10', dueDate: '2024-05-24', status: 'Active' },
                  { id: 2, book: '1984', member: 'Bob Smith', borrowDate: '2024-04-15', dueDate: '2024-04-29', status: 'Overdue' },
                  { id: 3, book: 'Design Patterns', member: 'Charlie Brown', borrowDate: '2024-05-18', dueDate: '2024-06-01', status: 'Active' },
              ],
              
              // Books Actions
              openCreate() { this.bookToEdit = null; this.view = 'form'; },
              openEdit(book) { this.bookToEdit = book; this.view = 'form'; },
              confirmDelete(id) { this.showDeleteModal = true; },
              
              // Members Actions
              openMemberCreate() { this.memberToEdit = null; this.memberView = 'form'; },
              openMemberEdit(member) { this.memberToEdit = member; this.memberView = 'form'; },
              confirmMemberDelete(id) { this.showMemberDeleteModal = true; },

              // Helper for dynamic header title
              getHeaderTitle() {
                  if (this.activeTab === 'books') {
                      return this.view === 'list' ? 'Books Inventory' : (this.bookToEdit ? 'Edit Book Details' : 'Add New Book');
                  }
                  if (this.activeTab === 'members') {
                      return this.memberView === 'list' ? 'Library Members' : (this.memberToEdit ? 'Edit Member Details' : 'Add New Member');
                  }
                  if (this.activeTab === 'loans') return 'Loans & Returns';
                  if (this.activeTab === 'reports') return 'Library Reports';
                  return 'Library System';
              }
          }">

        <!-- ========================================== -->
        <!-- AUTHENTICATION LAYOUT -->
        <!-- ========================================== -->
        <div x-show="!isAuthenticated" class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-50 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-100 via-slate-50 to-slate-50"></div>
            
            <div class="sm:mx-auto sm:w-full sm:max-w-md mb-6 text-center">
                <div class="flex justify-center items-center mb-4">
                    <svg class="w-12 h-12 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight">LibSys</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800" x-text="authView === 'login' ? 'Sign in to your account' : (authView === 'register' ? 'Create a new account' : 'Reset your password')"></h2>
                <p class="mt-2 text-sm text-slate-600" x-show="authView === 'login'">
                    Or <a href="#" @click.prevent="authView = 'register'" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">start your 14-day free trial</a>
                </p>
                <p class="mt-2 text-sm text-slate-600" x-show="authView === 'register'" style="display: none;">
                    Already have an account? <a href="#" @click.prevent="authView = 'login'" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Sign in instead</a>
                </p>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 shadow-xl shadow-slate-200/50 sm:rounded-2xl sm:px-10 border border-slate-100 relative">
                    
                    <!-- Login Form -->
                    <form x-show="authView === 'login'" @submit.prevent="login()" class="space-y-6" x-transition.opacity.duration.300ms>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="admin@libsys.com">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer">
                                <label for="remember-me" class="ml-2 block text-sm text-slate-900 cursor-pointer">
                                    Remember me
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" @click.prevent="authView = 'forgot'" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                    Forgot your password?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Sign in
                            </button>
                        </div>
                    </form>

                    <!-- Register Form -->
                    <form x-show="authView === 'register'" @submit.prevent="login()" class="space-y-5" style="display: none;" x-transition.opacity.duration.300ms>
                        <div>
                            <label for="reg-name" class="block text-sm font-medium text-slate-700">Full Name</label>
                            <div class="mt-1">
                                <input id="reg-name" name="name" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="John Doe">
                            </div>
                        </div>

                        <div>
                            <label for="reg-email" class="block text-sm font-medium text-slate-700">Email address</label>
                            <div class="mt-1">
                                <input id="reg-email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="john@example.com">
                            </div>
                        </div>

                        <div>
                            <label for="reg-password" class="block text-sm font-medium text-slate-700">Password</label>
                            <div class="mt-1">
                                <input id="reg-password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="Create a strong password">
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Create Account
                            </button>
                        </div>
                    </form>

                    <!-- Forgot Password Form -->
                    <form x-show="authView === 'forgot'" @submit.prevent="authView = 'login'" class="space-y-6" style="display: none;" x-transition.opacity.duration.300ms>
                        <div class="bg-indigo-50 text-indigo-700 p-4 rounded-lg text-sm">
                            Enter your email address and we will send you a link to reset your password.
                        </div>
                        
                        <div>
                            <label for="reset-email" class="block text-sm font-medium text-slate-700">Email address</label>
                            <div class="mt-1">
                                <input id="reset-email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="your-email@example.com">
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Send Reset Link
                            </button>
                            <button type="button" @click="authView = 'login'" class="w-full flex justify-center py-2.5 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Back to Sign In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- DASHBOARD LAYOUT -->
        <!-- ========================================== -->
        <div x-show="isAuthenticated" style="display: none;" class="h-screen flex overflow-hidden w-full">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col hidden md:flex z-20">
        <div class="h-16 flex items-center px-6 border-b border-slate-200">
            <svg class="w-8 h-8 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="text-xl font-bold text-slate-800">LibSys</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="#" @click.prevent="activeTab = 'books'" :class="activeTab === 'books' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Books Inventory
            </a>
            <a href="#" @click.prevent="activeTab = 'members'" :class="activeTab === 'members' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Members
            </a>
            <a href="#" @click.prevent="activeTab = 'loans'" :class="activeTab === 'loans' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Loans & Returns
            </a>
            <a href="#" @click.prevent="activeTab = 'reports'" :class="activeTab === 'reports' ? 'text-indigo-700 bg-indigo-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100'" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Reports
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin" class="w-9 h-9 rounded-full">
                    <div>
                        <p class="text-sm font-medium text-slate-800">Admin User</p>
                        <p class="text-xs text-slate-500">Librarian</p>
                    </div>
                </div>
                <button @click="logout()" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Sign out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <!-- Using Alpine.js to manage the state of the views (List, Create/Edit Form, Delete Modal) -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50">
        
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 z-10">
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button class="md:hidden text-slate-500 hover:text-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xl font-semibold text-slate-800" x-text="getHeaderTitle()">Books Inventory</h1>
            </div>
            
            <!-- Global Search -->
            <div class="hidden sm:flex items-center max-w-md w-full ml-8 relative" x-show="activeTab === 'books' || activeTab === 'members'">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow" placeholder="Search records...">
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-4">
                <button class="text-slate-500 hover:text-indigo-600 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <div class="flex-1 overflow-auto p-6 relative">

            <!-- FLASH MESSAGE (Example placeholder for backend session success) -->
            <!-- 
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 border-l-4 border-green-500 flex items-start shadow-sm">
                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif 
            -->

            <!-- ========================================== -->
            <!-- BOOKS TAB -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'books'">

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
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ISBN</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Copies</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                <!-- LOOP STARTS HERE (Simulated with Alpine for preview) -->
                                <!-- In real Laravel: @foreach($books as $book) ... @endforeach -->
                                <template x-for="book in books" :key="book.id">
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold">
                                                    <span x-text="book.title.charAt(0)"></span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-slate-900" x-text="book.title"></div>
                                                    <div class="text-sm text-slate-500" x-text="book.author"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-slate-600 font-mono" x-text="book.isbn"></span>
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
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <!-- Edit Button -->
                                                <button @click="openEdit(book)" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors tooltip-trigger" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <!-- Delete Button -->
                                                <button @click="confirmDelete(book.id)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors tooltip-trigger" title="Delete">
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
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">42</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 bg-indigo-50 text-sm font-medium text-indigo-600">1</a>
                                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">2</a>
                                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">3</a>
                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                    <form action="#" method="POST" class="p-6">
                        <!-- CSRF Token and Method Directives would go here -->
                        <!-- @csrf -->
                        <!-- @if(isset($book)) @method('PUT') @endif -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Book Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="bookToEdit ? bookToEdit.title : ''" required placeholder="e.g., The Great Gatsby">
                            </div>

                            <!-- Author -->
                            <div>
                                <label for="author" class="block text-sm font-medium text-slate-700 mb-1">Author / Writer <span class="text-red-500">*</span></label>
                                <input type="text" name="author" id="author" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="bookToEdit ? bookToEdit.author : ''" required placeholder="e.g., F. Scott Fitzgerald">
                            </div>

                            <!-- ISBN -->
                            <div>
                                <label for="isbn" class="block text-sm font-medium text-slate-700 mb-1">ISBN Number</label>
                                <input type="text" name="isbn" id="isbn" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="bookToEdit ? bookToEdit.isbn : ''" placeholder="e.g., 978-0743273565">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select id="category" name="category" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                                    <option value="" disabled selected>Select a category</option>
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
                                <input type="number" name="copies" id="copies" min="1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="bookToEdit ? bookToEdit.copies : '1'" required>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Synopsis / Description</label>
                                <textarea id="description" name="description" rows="4" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" placeholder="Brief summary of the book..."></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
                            <button type="button" @click="view = 'list'" class="bg-white py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" x-text="bookToEdit ? 'Save Changes' : 'Save New Book'">
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- DELETE VIEW (Modal) -->
            <!-- ========================================== -->
            <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <!-- Background overlay -->
                    <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <!-- Center modal trick -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal Panel -->
                    <div x-show="showDeleteModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Delete Book Record</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500">Are you sure you want to delete this book from the inventory? This action cannot be undone and will remove all associated loan records.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                            <!-- Note: In a real app, this would be a form submitting a DELETE request -->
                            <form action="#" method="POST" class="sm:flex sm:flex-row-reverse w-full">
                                <!-- @csrf -->
                                <!-- @method('DELETE') -->
                                <button type="button" @click="showDeleteModal = false; /* Simulate delete */ view = 'list'" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Confirm Delete
                                </button>
                                <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            </div> <!-- End Books Tab -->

            <!-- ========================================== -->
            <!-- MEMBERS TAB -->
            <!-- ========================================== -->
            <div x-show="activeTab === 'members'" style="display: none;">
                
                <!-- MEMBERS LIST VIEW -->
                <div x-show="memberView === 'list'" x-transition.opacity.duration.300ms class="space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                        <div class="flex items-center gap-3">
                            <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-sm border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-slate-700">
                                <option>All Statuses</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <button @click="openMemberCreate()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Member
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Member Info</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Joined Date</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    <template x-for="member in members" :key="member.id">
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 flex-shrink-0 bg-slate-200 rounded-full flex items-center justify-center text-slate-600 font-bold overflow-hidden">
                                                        <img :src="'https://ui-avatars.com/api/?name=' + member.name + '&background=e2e8f0&color=475569'" :alt="member.name" class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-slate-900" x-text="member.name"></div>
                                                        <div class="text-sm text-slate-500">ID: #<span x-text="'M-' + member.id.toString().padStart(4, '0')"></span></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900" x-text="member.email"></div>
                                                <div class="text-sm text-slate-500" x-text="member.phone"></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600" x-text="member.joined"></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                      :class="member.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800'">
                                                    <span x-text="member.status"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button @click="openMemberEdit(member)" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

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
                        <form action="#" method="POST" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="memberToEdit ? memberToEdit.name : ''" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="memberToEdit ? memberToEdit.email : ''" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                                    <input type="text" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border" :value="memberToEdit ? memberToEdit.phone : ''">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                    <select class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-8 pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
                                <button type="button" @click="memberView = 'list'" class="bg-white py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Cancel</button>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors" x-text="memberToEdit ? 'Save Changes' : 'Register Member'"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
                            <p class="text-2xl font-bold text-slate-800">1,248</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Active Members</p>
                            <p class="text-2xl font-bold text-slate-800">452</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Active Loans</p>
                            <p class="text-2xl font-bold text-slate-800">89</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center">
                        <div class="p-3 rounded-xl bg-red-50 text-red-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Overdue Books</p>
                            <p class="text-2xl font-bold text-slate-800">12</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-6">Loans Overview (Last 6 Months)</h3>
                        <!-- Simple CSS Bar Chart Placeholder -->
                        <div class="h-64 flex items-end justify-between gap-4 border-b border-l border-slate-200 pb-2 pl-2 relative">
                            <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 40%"></div>
                            <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 65%"></div>
                            <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 45%"></div>
                            <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 80%"></div>
                            <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 55%"></div>
                            <div class="w-full bg-indigo-500 hover:bg-indigo-600 transition-colors rounded-t-sm" style="height: 90%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm font-medium text-slate-500 px-2">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-6">Recent Activity</h3>
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-green-500 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-slate-800"><strong>Alice Johnson</strong> returned <span class="text-indigo-600 cursor-pointer">The Great Gatsby</span></p>
                                    <p class="text-xs text-slate-500 mt-0.5">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-amber-500 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-slate-800"><strong>Bob Smith</strong> borrowed <span class="text-indigo-600 cursor-pointer">Clean Code</span></p>
                                    <p class="text-xs text-slate-500 mt-0.5">5 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-indigo-500 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-slate-800">New member <strong>Charlie Brown</strong> registered</p>
                                    <p class="text-xs text-slate-500 mt-0.5">1 day ago</p>
                                </div>
                            </div>
                             <div class="flex items-start gap-4">
                                <div class="w-2.5 h-2.5 mt-1.5 rounded-full bg-red-500 shrink-0"></div>
                                <div>
                                    <p class="text-sm text-slate-800">Automated notice sent for <span class="text-indigo-600 cursor-pointer">1984</span> (Overdue)</p>
                                    <p class="text-xs text-slate-500 mt-0.5">2 days ago</p>
                                </div>
                            </div>
                        </div>
                        <button class="w-full mt-6 py-2 text-sm text-indigo-600 font-medium bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">View All Activity</button>
                    </div>
                </div>
            </div>

        </div>
    </main>
    
    </div> <!-- End Dashboard Layout Wrapper -->
</body>
</html>