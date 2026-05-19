<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Library Management System</title>
    <!-- Include Tailwind CSS via CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Alpine.js for simple UI interactions (modals, dropdowns) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('libraryApp', () => ({
                isAuthenticated: false,
                authView: 'login',
                isLoading: false,
                authError: '',
                authSuccess: '',

                apiHeaders() {
                    return {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    };
                },

                async readJsonResponse(response) {
                    const contentType = response.headers.get('content-type') || '';

                    if (contentType.includes('application/json')) {
                        return await response.json();
                    }

                    const body = await response.text();

                    throw new Error(body.trim().startsWith('<!DOCTYPE')
                        ? 'Server returned an HTML page instead of JSON. Check the API route and validation errors.'
                        : body || 'Server returned an unexpected response.');
                },

                init() {
                    this.loadBooks();
                    this.loadMembers();
                },

                loginForm: {
                    email: '',
                    password: '',
                    rememberMe: false,
                },

                    bookForm: {
                        title: '',
                        author: '',
                        isbn: '',
                        published_year: new Date().getFullYear(),
                        category: 'Fiction',
                        quantity: 1,
                        available_quantity: 1,
                        description: '',
                        price: 0,
                    },

                    normalizeBook(book) {
                        return {
                            id: Number(book.id),
                            title: book.title || '',
                            author: book.author || '',
                            isbn: book.isbn || '',
                            category: book.category || 'Uncategorized',
                            published_year: book.published_year || new Date().getFullYear(),
                            description: book.description || '',
                            price: Number(book.price || 0),
                            quantity: Number(book.quantity ?? book.copies ?? 0),
                            available_quantity: Number(book.available_quantity ?? book.copies ?? 0),
                            copies: Number(book.available_quantity ?? book.quantity ?? book.copies ?? 0),
                            status: Number(book.available_quantity ?? book.copies ?? 0) > 0 ? 'Available' : 'Borrowed',
                        };
                    },

                    async loadBooks() {
                        try {
                            const response = await fetch('/api/books', {
                                headers: this.apiHeaders(),
                            });

                            const data = await this.readJsonResponse(response);

                            if (response.ok && data.success && Array.isArray(data.data)) {
                                this.books = data.data.map((book) => this.normalizeBook(book));
                            }
                        } catch (error) {
                            console.error('Failed to load books:', error);
                        }
                    },

                async handleLogin() {
                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const response = await fetch('/api/auth/login', {
                            method: 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify({
                                email: this.loginForm.email,
                                password: this.loginForm.password
                            })
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.authSuccess = 'Login successful! Redirecting...';
                            this.loginForm = { email: '', password: '', rememberMe: false };
                            setTimeout(() => {
                                this.isAuthenticated = true;
                                this.loadBooks();
                            }, 500);
                        } else {
                            this.authError = data.message || 'Login failed';
                        }
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async logout() {
                    try {
                        await fetch('/api/auth/logout', {
                            method: 'POST',
                            headers: this.apiHeaders()
                        });
                        this.isAuthenticated = false;
                        this.authView = 'login';
                    } catch (error) {
                        console.error('Logout error:', error);
                    }
                },

                activeTab: 'books',
                sidebarOpen: false,
                view: 'list',
                showDeleteModal: false,
                bookToDelete: null,
                bookToEdit: null,
                books: [
                    
                ],

                memberView: 'list',
                showMemberDeleteModal: false,
                memberToEdit: null,
                members: [
                    { id: 1, name: 'Alice Johnson', email: 'alice@example.com', phone: '(555) 123-4567', status: 'Active', joined: 'Oct 12, 2023' },
                    { id: 2, name: 'Bob Smith', email: 'bob.smith@example.com', phone: '(555) 987-6543', status: 'Inactive', joined: 'Nov 05, 2023' },
                    { id: 3, name: 'Charlie Brown', email: 'cbrown@example.com', phone: '(555) 456-7890', status: 'Active', joined: 'Jan 20, 2024' },
                ],

                loans: [
                    { id: 1, book: 'Clean Code', member: 'Alice Johnson', borrowDate: '2024-05-10', dueDate: '2024-05-24', status: 'Active' },
                    { id: 2, book: '1984', member: 'Bob Smith', borrowDate: '2024-04-15', dueDate: '2024-04-29', status: 'Overdue' },
                    { id: 3, book: 'Design Patterns', member: 'Charlie Brown', borrowDate: '2024-05-18', dueDate: '2024-06-01', status: 'Active' },
                ],

                openCreate() {
                    this.bookToEdit = null;
                    this.bookForm = {
                        title: '',
                        author: '',
                        isbn: '',
                        published_year: new Date().getFullYear(),
                        category: 'Fiction',
                        quantity: 1,
                        available_quantity: 1,
                        description: '',
                        price: 0,
                    };
                    this.view = 'form';
                },

                openEdit(book) {
                    this.bookToEdit = book;
                    this.bookForm = {
                        title: book.title || '',
                        author: book.author || '',
                        isbn: book.isbn || '',
                        published_year: book.published_year || new Date().getFullYear(),
                        category: book.category || 'Fiction',
                        quantity: book.quantity || book.copies || 1,
                        available_quantity: book.available_quantity ?? book.copies ?? 1,
                        description: book.description || '',
                        price: book.price || 0,
                    };
                    this.view = 'form';
                },

                async handleBookSubmit() {
                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const isEditing = !!this.bookToEdit?.id;
                        const response = await fetch(isEditing ? `/api/books/${this.bookToEdit.id}` : '/api/books', {
                            method: isEditing ? 'PUT' : 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify(this.bookForm),
                        });

                        const data = await this.readJsonResponse(response);

                        if (!response.ok || !data.success) {
                            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                            this.authError = data.message || errors || 'Unable to save book';
                            return;
                        }

                        const savedBook = data.data;
                        const normalizedBook = this.normalizeBook(savedBook);

                        if (isEditing) {
                            this.books = this.books.map((book) => book.id === normalizedBook.id ? normalizedBook : book);
                            this.authSuccess = 'Book updated successfully!';
                        } else {
                            this.books.unshift(normalizedBook);
                            this.authSuccess = 'Book saved successfully!';
                        }

                        this.bookToEdit = null;
                        this.view = 'list';
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },
                confirmDelete(id) {
                    this.bookToDelete = id;
                    this.showDeleteModal = true;
                },

                async deleteBook() {
                    if (!this.bookToDelete) {
                        return;
                    }

                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const response = await fetch(`/api/books/${this.bookToDelete}`, {
                            method: 'DELETE',
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);

                        if (!response.ok || !data.success) {
                            this.authError = data.message || 'Unable to delete book';
                            return;
                        }

                        this.books = this.books.filter((book) => Number(book.id) !== Number(this.bookToDelete));
                        this.showDeleteModal = false;
                        this.bookToDelete = null;
                        this.authSuccess = 'Book deleted successfully!';
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                openMemberCreate() { 
                    this.memberToEdit = null; 
                    this.memberForm = { name: '', email: '', phone: '', status: 'Active' };
                    this.memberView = 'form'; 
                },
                openMemberEdit(member) { 
                    this.memberToEdit = member;
                    this.memberForm = {
                        name: member.name || '',
                        email: member.email || '',
                        phone: member.phone || '',
                        status: member.status || 'Active',
                    };
                    this.memberView = 'form'; 
                },
                confirmMemberDelete(id) { this.showMemberDeleteModal = true; },

                async loadMembers() {
                    try {
                        const response = await fetch('/api/members', {
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);

                        if (response.ok && data.success && Array.isArray(data.data)) {
                            this.members = data.data.map((member) => ({
                                id: Number(member.id),
                                name: member.name || '',
                                email: member.email || '',
                                phone: member.phone || '',
                                status: member.status || 'Active',
                                joined: member.joined_at ? new Date(member.joined_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '',
                            }));
                        }
                    } catch (error) {
                        console.error('Failed to load members:', error);
                    }
                },

                memberForm: {
                    name: '',
                    email: '',
                    phone: '',
                    status: 'Active',
                },

                async handleMemberSubmit() {
                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const isEditing = !!this.memberToEdit?.id;
                        const response = await fetch(isEditing ? `/api/members/${this.memberToEdit.id}` : '/api/members', {
                            method: isEditing ? 'PUT' : 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify(this.memberForm),
                        });

                        const data = await this.readJsonResponse(response);

                        if (!response.ok || !data.success) {
                            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                            this.authError = data.message || errors || 'Unable to save member';
                            return;
                        }

                        const savedMember = data.data;
                        const normalizedMember = {
                            id: Number(savedMember.id),
                            name: savedMember.name || '',
                            email: savedMember.email || '',
                            phone: savedMember.phone || '',
                            status: savedMember.status || 'Active',
                            joined: savedMember.joined_at ? new Date(savedMember.joined_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '',
                        };

                        if (isEditing) {
                            this.members = this.members.map((member) => member.id === normalizedMember.id ? normalizedMember : member);
                            this.authSuccess = 'Member updated successfully!';
                        } else {
                            this.members.unshift(normalizedMember);
                            this.authSuccess = 'Member registered successfully!';
                        }

                        this.memberToEdit = null;
                        this.memberView = 'list';
                        this.memberForm = { name: '', email: '', phone: '', status: 'Active' };
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async deleteMember() {
                    if (!this.showMemberDeleteModal || !this.memberToEdit) {
                        return;
                    }

                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const response = await fetch(`/api/members/${this.memberToEdit.id}`, {
                            method: 'DELETE',
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);

                        if (!response.ok || !data.success) {
                            this.authError = data.message || 'Unable to delete member';
                            return;
                        }

                        this.members = this.members.filter((member) => Number(member.id) !== Number(this.memberToEdit.id));
                        this.showMemberDeleteModal = false;
                        this.memberToEdit = null;
                        this.memberView = 'list';
                        this.authSuccess = 'Member deleted successfully!';
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

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
            }));
        });
    </script>
    <style>
        /* Ensure body has correct styling */
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
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
        
        /* Form input styling as fallback */
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            box-sizing: border-box;
        }
        
        /* Ensure form visibility */
        form {
            display: block;
            width: 100%;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-screen w-full" x-data="libraryApp()">

    @include('auth.login')
    
    <div x-show="isAuthenticated" style="display: none;" class="h-screen flex overflow-hidden w-full relative">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden" style="display: none;"></div>
        
        @include('components.sidebar')
        
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50">
            @include('components.header')
            
            <div class="flex-1 overflow-auto p-6 relative">
                @include('components.flash-message')

                @include('tabs.books')
                @include('tabs.members')
                @include('tabs.loans')
                @include('tabs.reports')
            </div>
        </main>
    </div>
</body>
</html>
