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
                    this.loadCurrentUser();
                    this.loadLoans();
                    this.loadSystemLogo();
                    this.loadNotifications();
                },

                loginForm: {
                    email: '',
                    password: '',
                    rememberMe: false,
                },

                    bookForm: {
                        title: '',
                        author: '',
                        published_year: new Date().getFullYear(),
                        category: 'Fiction',
                        quantity: 1,
                        available_quantity: 1,
                        description: '',
                        image: '',
                    },
                    // Pagination state for books list
                    currentPage: 1,
                    perPage: 10,
                    totalBooks: 0,
                    lastPage: 1,
                    pages: [],

                    normalizeBook(book) {
                        return {
                            id: Number(book.id),
                            title: book.title || '',
                            author: book.author || '',
                            image: book.image || '',
                            category: book.category || 'Uncategorized',
                            published_year: book.published_year || new Date().getFullYear(),
                            description: book.description || '',
                            quantity: Number(book.quantity ?? book.copies ?? 0),
                            available_quantity: Number(book.available_quantity ?? book.copies ?? 0),
                            copies: Number(book.available_quantity ?? book.quantity ?? book.copies ?? 0),
                            status: Number(book.available_quantity ?? book.copies ?? 0) > 0 ? 'Available' : 'Borrowed',
                            createdAt: book.created_at || book.createdAt || '',
                            updatedAt: book.updated_at || book.updatedAt || '',
                        };
                    },

                    async loadBooks() {
                        try {
                            const url = `/api/books?page=${this.currentPage}&per_page=${this.perPage}`;
                            const response = await fetch(url, { headers: this.apiHeaders() });
                            const data = await this.readJsonResponse(response);

                            if (response.ok && data.success && Array.isArray(data.data)) {
                                this.books = data.data.map((book) => this.normalizeBook(book));

                                // Update pagination metadata if provided
                                if (data.meta) {
                                    this.currentPage = Number(data.meta.current_page || this.currentPage);
                                    this.perPage = Number(data.meta.per_page || this.perPage);
                                    this.totalBooks = Number(data.meta.total || this.totalBooks);
                                    this.lastPage = Number(data.meta.last_page || this.lastPage);
                                } else {
                                    // Fallback: compute basic totals
                                    this.totalBooks = this.books.length;
                                    this.lastPage = 1;
                                }

                                this.buildPagesArray();
                                this.refreshReportData();
                            }
                        } catch (error) {
                            console.error('Failed to load books:', error);
                        }
                    },

                    buildPagesArray() {
                        const pages = [];
                        const total = this.lastPage || 1;
                        const maxButtons = 5;
                        let start = Math.max(1, this.currentPage - Math.floor(maxButtons / 2));
                        let end = start + maxButtons - 1;

                        if (end > total) {
                            end = total;
                            start = Math.max(1, end - maxButtons + 1);
                        }

                        for (let p = start; p <= end; p++) pages.push(p);
                        this.pages = pages;
                    },

                    gotoPage(p) {
                        if (p < 1 || p > this.lastPage || p === this.currentPage) return;
                        this.currentPage = p;
                        this.loadBooks();
                    },

                    prevPage() {
                        if (this.currentPage > 1) {
                            this.currentPage -= 1;
                            this.loadBooks();
                        }
                    },

                    nextPage() {
                        if (this.currentPage < this.lastPage) {
                            this.currentPage += 1;
                            this.loadBooks();
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
                                password: this.loginForm.password,
                                remember: this.loginForm.rememberMe ? 1 : 0,
                            })
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.authSuccess = 'Login successful! Redirecting...';
                            this.loginForm.password = '';
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

                reportSummary: {
                    totalBooks: 0,
                    activeMembers: 0,
                    activeLoans: 0,
                    overdueBooks: 0,
                },
                reportLoanTrend: [],
                reportRecentActivity: [],
                reportLastUpdated: '',

                // Loans modal state
                showLoanModal: false,
                loanForm: {
                    book_id: null,
                    member_id: null,
                },

                // Notification state
                notifications: [],
                showNotificationPanel: false,
                notificationCount: 0,

                addNotification(message, type = 'info', action = '') {
                    const notification = {
                        id: Date.now(),
                        message: message,
                        type: type, // 'success', 'error', 'info', 'warning'
                        action: action,
                        timestamp: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                        read: false,
                    };
                    
                    this.notifications.unshift(notification);
                    this.notificationCount = this.notifications.filter(n => !n.read).length;
                    
                    // Save notification to database
                    this.saveNotificationToDatabase(message, type, action);
                    
                    // Auto-remove success notifications after 3 seconds
                    if (type === 'success') {
                        setTimeout(() => {
                            this.notifications = this.notifications.filter(n => n.id !== notification.id);
                            this.notificationCount = this.notifications.filter(n => !n.read).length;
                        }, 3000);
                    }
                },

                async saveNotificationToDatabase(message, type, action) {
                    try {
                        await fetch('/api/notifications', {
                            method: 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify({
                                message: message,
                                type: type,
                                action: action || null,
                            })
                        });
                    } catch (error) {
                        console.error('Error saving notification to database:', error);
                    }
                },

                async loadNotifications() {
                    try {
                        const response = await fetch('/api/notifications', {
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);

                        if (response.ok && data.success && Array.isArray(data.data)) {
                            this.notifications = data.data.map(n => ({
                                id: n.id,
                                message: n.message || '',
                                type: n.type || 'info',
                                action: n.action || '',
                                timestamp: new Date(n.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                                read: n.read || false,
                            }));
                            this.notificationCount = data.unread_count || 0;
                        }
                    } catch (error) {
                        console.error('Failed to load notifications:', error);
                    }
                },

                async markNotificationAsRead(notificationId) {
                    try {
                        await fetch(`/api/notifications/${notificationId}/read`, {
                            method: 'PATCH',
                            headers: this.apiHeaders(),
                        });
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                    }
                },

                toggleNotificationPanel() {
                    this.showNotificationPanel = !this.showNotificationPanel;
                    // Mark all as read when opening panel
                    if (this.showNotificationPanel) {
                        this.notifications.forEach(n => {
                            if (!n.read) {
                                n.read = true;
                                this.markNotificationAsRead(n.id);
                            }
                        });
                        this.notificationCount = 0;
                    }
                },

                async removeNotification(id) {
                    try {
                        await fetch(`/api/notifications/${id}`, {
                            method: 'DELETE',
                            headers: this.apiHeaders(),
                        });
                        this.notifications = this.notifications.filter(n => n.id !== id);
                        this.notificationCount = this.notifications.filter(n => !n.read).length;
                    } catch (error) {
                        console.error('Error removing notification:', error);
                    }
                },

                async clearAllNotifications() {
                    try {
                        await fetch('/api/notifications', {
                            method: 'DELETE',
                            headers: this.apiHeaders(),
                        });
                        this.notifications = [];
                        this.notificationCount = 0;
                    } catch (error) {
                        console.error('Error clearing notifications:', error);
                    }
                },

                openCreate() {
                    this.bookToEdit = null;
                    this.bookForm = {
                        title: '',
                        author: '',
                        published_year: new Date().getFullYear(),
                        category: 'Fiction',
                        quantity: 1,
                        available_quantity: 1,
                        description: '',
                        image: '',
                    };
                    this.view = 'form';
                },

                openEdit(book) {
                    this.bookToEdit = book;
                    this.bookForm = {
                        title: book.title || '',
                        author: book.author || '',
                        published_year: book.published_year || new Date().getFullYear(),
                        category: book.category || 'Fiction',
                        quantity: book.quantity || book.copies || 1,
                        available_quantity: book.available_quantity ?? book.copies ?? 1,
                        description: book.description || '',
                        image: book.image || '',
                    };
                    this.view = 'form';
                },

                openView(book) {
                    this.bookToEdit = book;
                    this.bookForm = {
                        title: book.title || '',
                        author: book.author || '',
                        published_year: book.published_year || new Date().getFullYear(),
                        category: book.category || 'Fiction',
                        quantity: book.quantity || book.copies || 1,
                        available_quantity: book.available_quantity ?? book.copies ?? 1,
                        description: book.description || '',
                        image: book.image || '',
                    };
                    this.view = 'view';
                },

                syncAvailableCopies() {
                    // Calculate available copies: Total - Active Loans
                    const totalCopies = parseInt(this.bookForm.quantity) || 0;
                    const activeLoans = this.loans.filter(loan => 
                        loan.book === this.bookForm.title && loan.status === 'Active'
                    ).length;
                    this.bookForm.available_quantity = Math.max(0, totalCopies - activeLoans);
                },

                async issueLoan(bookId, memberId) {
                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const borrowDate = new Date().toISOString().split('T')[0];
                        const dueDate = new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

                        const response = await fetch('/api/loans', {
                            method: 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify({ book_id: bookId, member_id: memberId, borrow_date: borrowDate, due_date: dueDate }),
                        });

                        const data = await this.readJsonResponse(response);

                        if (!response.ok || !data.success) {
                            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                            this.authError = data.message || errors || 'Unable to issue loan';
                            this.addNotification('Error: ' + (this.authError), 'error');
                            return;
                        }

                        const loan = data.data;
                        const memberName = this.members.find(m => m.id === memberId)?.name || 'Member';
                        const bookTitle = this.books.find(b => b.id === bookId)?.title || 'Book';

                        // Normalize and add to local state
                        // Refresh the loans list from server to keep state consistent
                        await this.loadLoans();

                        // Update book available copies locally (optimistic / quick update)
                        this.books = this.books.map(b => b.id === Number(loan.book_id) ? { ...b, available_quantity: Math.max(0, b.available_quantity - 1) } : b);

                        this.authSuccess = 'Loan issued successfully!';
                        this.addNotification(`📤 Book "${bookTitle}" issued to ${memberName}. Due date: ${dueDate}`, 'success', 'Book Issued');
                        this.refreshReportData();
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                        this.addNotification('Network error: ' + error.message, 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                async returnBook(loanId) {
                    this.isLoading = true;
                    this.authError = '';
                    this.authSuccess = '';

                    try {
                        const response = await fetch(`/api/loans/${loanId}/return`, {
                            method: 'PATCH',
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);
                        if (!response.ok || !data.success) {
                            this.authError = data.message || 'Unable to return loan';
                            this.addNotification('Error: ' + (this.authError), 'error');
                            return;
                        }

                        const loan = data.data;
                        const memberName = loan.member_name || 'Member';
                        const bookTitle = loan.book_title || 'Book';

                        // Update local loans and books state
                        // Refresh loans from server
                        await this.loadLoans();

                        // Update books locally as well
                        this.books = this.books.map(b => b.id === Number(loan.book_id) ? { ...b, available_quantity: b.available_quantity + 1 } : b);

                        this.authSuccess = 'Book returned successfully!';
                        this.addNotification(`📥 Book "${bookTitle}" returned by ${memberName}. Thank you!`, 'success', 'Book Returned');
                        this.refreshReportData();
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                        this.addNotification('Network error: ' + error.message, 'error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                async loadLoans() {
                    try {
                        const response = await fetch('/api/loans', { headers: this.apiHeaders() });
                        const data = await this.readJsonResponse(response);

                        if (response.ok && data.success && Array.isArray(data.data)) {
                            this.loans = data.data.map(l => ({
                                id: Number(l.id),
                                book: l.book_title || (l.book?.title ?? ''),
                                bookImage: (l.book && l.book.image) ? l.book.image : (l.book_image ?? ''),
                                member: l.member_name || (l.member?.name ?? ''),
                                borrowDate: l.borrow_date || '',
                                dueDate: l.due_date || '',
                                returnedAt: l.returned_at || l.returnedAt || '',
                                createdAt: l.created_at || l.createdAt || '',
                                updatedAt: l.updated_at || l.updatedAt || '',
                                status: this.normalizeLoanStatus({
                                    status: l.status || 'Active',
                                    dueDate: l.due_date || '',
                                    returnedAt: l.returned_at || l.returnedAt || '',
                                }),
                            }));
                            this.refreshReportData();
                        }
                    } catch (error) {
                        console.error('Failed to load loans:', error);
                    }
                },

                handleBookImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Check file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            this.authError = 'Image file must be smaller than 5MB';
                            return;
                        }
                        
                        // Check file type
                        if (!file.type.startsWith('image/')) {
                            this.authError = 'Please select a valid image file';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.bookForm.image = e.target.result;
                            this.authError = '';
                        };
                        reader.readAsDataURL(file);
                    }
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
                            this.addNotification('Error: ' + (this.authError), 'error');
                            return;
                        }

                        const savedBook = data.data;
                        const normalizedBook = this.normalizeBook(savedBook);

                        if (isEditing) {
                            this.books = this.books.map((book) => book.id === normalizedBook.id ? normalizedBook : book);
                            this.authSuccess = 'Book updated successfully!';
                            this.addNotification(`📚 Book "${normalizedBook.title}" has been updated.`, 'success', 'Book Updated');
                        } else {
                            this.books.unshift(normalizedBook);
                            this.totalBooks = Math.max(0, Number(this.totalBooks || this.books.length) + 1);
                            this.authSuccess = 'Book saved successfully!';
                            this.addNotification(`✅ New book "${normalizedBook.title}" has been added to the inventory.`, 'success', 'Book Added');
                        }

                        this.bookToEdit = null;
                        this.view = 'list';
                        this.refreshReportData();
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                        this.addNotification('Network error: ' + error.message, 'error');
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
                            this.addNotification('Error: ' + (this.authError), 'error');
                            return;
                        }

                        const deletedBookTitle = this.books.find(b => Number(b.id) === Number(this.bookToDelete))?.title || 'Book';
                        this.books = this.books.filter((book) => Number(book.id) !== Number(this.bookToDelete));
                        this.totalBooks = Math.max(0, this.totalBooks - 1);
                        this.refreshReportData();
                        this.showDeleteModal = false;
                        this.bookToDelete = null;
                        this.authSuccess = 'Book deleted successfully!';
                        this.addNotification(`🗑️ Book "${deletedBookTitle}" has been removed from the inventory.`, 'success', 'Book Deleted');
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                        this.addNotification('Network error: ' + error.message, 'error');
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
                                joinedAt: member.joined_at || member.joinedAt || '',
                                createdAt: member.created_at || member.createdAt || '',
                                updatedAt: member.updated_at || member.updatedAt || '',
                            }));
                            this.refreshReportData();
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
                        this.refreshReportData();
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
                        this.refreshReportData();
                    } catch (error) {
                        this.authError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                // Settings related properties
                currentUser: {
                    id: '',
                    name: '',
                    email: '',
                    profile_image: '',
                },
                profileForm: {
                    name: '',
                    email: '',
                },
                passwordForm: {
                    current_password: '',
                    new_password: '',
                    password_confirmation: '',
                },
                settingsSuccess: '',
                settingsError: '',
                logoPreview: '',
                logoFile: null,
                logoForm: {
                    name: '',
                },
                systemLogo: {
                    path: '',
                    name: 'LibSys',
                },

                async loadCurrentUser() {
                    try {
                        const response = await fetch('/api/auth/user', {
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.isAuthenticated = true;
                            this.currentUser = {
                                id: data.user.id,
                                name: data.user.name || '',
                                email: data.user.email || '',
                                profile_image: data.user.profile_image || '',
                            };
                            this.profileForm = {
                                name: this.currentUser.name,
                                email: this.currentUser.email,
                            };
                        }
                    } catch (error) {
                        console.error('Failed to load user:', error);
                    }
                },

                async loadSystemLogo() {
                    try {
                        const response = await fetch('/api/auth/get-logo', {
                            headers: this.apiHeaders(),
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success && data.logo) {
                            this.systemLogo = {
                                path: data.logo.logo_path || '',
                                name: data.logo.logo_name || 'LibSys',
                            };
                        }
                    } catch (error) {
                        console.error('Failed to load logo:', error);
                    }
                },

                parseDate(value) {
                    if (!value) {
                        return null;
                    }

                    const parsed = new Date(value);
                    return Number.isNaN(parsed.getTime()) ? null : parsed;
                },

                startOfToday() {
                    const date = new Date();
                    date.setHours(0, 0, 0, 0);
                    return date;
                },

                isLoanOverdue(loan) {
                    if (String(loan.status || '').toLowerCase() === 'returned') {
                        return false;
                    }

                    const dueDate = this.parseDate(loan.dueDate || loan.due_date);
                    if (!dueDate) {
                        return false;
                    }

                    return dueDate.getTime() < this.startOfToday().getTime();
                },

                normalizeLoanStatus(loan) {
                    if (loan.returnedAt) {
                        return 'Returned';
                    }

                    if (String(loan.status || '').toLowerCase() === 'returned') {
                        return 'Returned';
                    }

                    return this.isLoanOverdue(loan) ? 'Overdue' : (loan.status || 'Active');
                },

                formatRelativeTime(value) {
                    const date = this.parseDate(value);
                    if (!date) {
                        return 'Recently';
                    }

                    const diffInMinutes = Math.max(0, Math.floor((Date.now() - date.getTime()) / 60000));

                    if (diffInMinutes < 1) {
                        return 'just now';
                    }

                    if (diffInMinutes < 60) {
                        return `${diffInMinutes} minute${diffInMinutes === 1 ? '' : 's'} ago`;
                    }

                    const diffInHours = Math.floor(diffInMinutes / 60);
                    if (diffInHours < 24) {
                        return `${diffInHours} hour${diffInHours === 1 ? '' : 's'} ago`;
                    }

                    const diffInDays = Math.floor(diffInHours / 24);
                    if (diffInDays < 30) {
                        return `${diffInDays} day${diffInDays === 1 ? '' : 's'} ago`;
                    }

                    const diffInMonths = Math.floor(diffInDays / 30);
                    if (diffInMonths < 12) {
                        return `${diffInMonths} month${diffInMonths === 1 ? '' : 's'} ago`;
                    }

                    const diffInYears = Math.floor(diffInMonths / 12);
                    return `${diffInYears} year${diffInYears === 1 ? '' : 's'} ago`;
                },

                buildMonthlyLoanTrend() {
                    const months = [];
                    const monthCounts = {};
                    const baseDate = new Date();
                    baseDate.setDate(1);
                    baseDate.setHours(0, 0, 0, 0);

                    for (let offset = 5; offset >= 0; offset -= 1) {
                        const monthDate = new Date(baseDate.getFullYear(), baseDate.getMonth() - offset, 1);
                        const key = `${monthDate.getFullYear()}-${String(monthDate.getMonth() + 1).padStart(2, '0')}`;
                        months.push({
                            key,
                            label: monthDate.toLocaleDateString('en-US', { month: 'short' }),
                            count: 0,
                        });
                    }

                    this.loans.forEach((loan) => {
                        const date = this.parseDate(loan.borrowDate || loan.borrow_date);
                        if (!date) {
                            return;
                        }

                        const key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
                        monthCounts[key] = (monthCounts[key] || 0) + 1;
                    });

                    return months.map((month) => ({
                        ...month,
                        count: monthCounts[month.key] || 0,
                    }));
                },

                buildRecentActivity() {
                    const activityItems = [];

                    this.loans.forEach((loan) => {
                        const status = String(loan.status || '').toLowerCase();
                        const activityDate = this.parseDate(loan.returnedAt || loan.updatedAt || loan.borrowDate);
                        if (!activityDate) {
                            return;
                        }

                        const isReturned = status === 'returned' || !!loan.returnedAt;
                        const isOverdue = status === 'overdue' || this.isLoanOverdue(loan);

                        activityItems.push({
                            type: isReturned ? 'returned' : (isOverdue ? 'overdue' : 'borrowed'),
                            title: isReturned
                                ? `${loan.member} returned ${loan.book}`
                                : `${loan.member} borrowed ${loan.book}`,
                            subtitle: this.formatRelativeTime(activityDate),
                            sortTime: activityDate.getTime(),
                            dotClass: isReturned ? 'bg-green-500' : (isOverdue ? 'bg-red-500' : 'bg-amber-500'),
                        });
                    });

                    this.members.forEach((member) => {
                        const activityDate = this.parseDate(member.joinedAt || member.createdAt);
                        if (!activityDate) {
                            return;
                        }

                        activityItems.push({
                            type: 'member',
                            title: `New member ${member.name} registered`,
                            subtitle: this.formatRelativeTime(activityDate),
                            sortTime: activityDate.getTime(),
                            dotClass: 'bg-indigo-500',
                        });
                    });

                    return activityItems
                        .sort((left, right) => right.sortTime - left.sortTime)
                        .slice(0, 4);
                },

                refreshReportData() {
                    this.reportSummary = {
                        totalBooks: Number(this.totalBooks || this.books.length || 0),
                        activeMembers: this.members.filter((member) => String(member.status || '').toLowerCase() === 'active').length,
                        activeLoans: this.loans.filter((loan) => String(loan.status || '').toLowerCase() === 'active').length,
                        overdueBooks: this.loans.filter((loan) => String(loan.status || '').toLowerCase() === 'overdue').length,
                    };

                    this.reportLoanTrend = this.buildMonthlyLoanTrend();
                    this.reportRecentActivity = this.buildRecentActivity();
                    this.reportLastUpdated = new Date().toLocaleString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                    });
                },

                async updateProfile() {
                    this.isLoading = true;
                    this.settingsError = '';
                    this.settingsSuccess = '';

                    try {
                        const response = await fetch('/api/auth/update-profile', {
                            method: 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify(this.profileForm),
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.currentUser.name = this.profileForm.name;
                            this.currentUser.email = this.profileForm.email;
                            this.settingsSuccess = 'Profile updated successfully!';
                        } else {
                            this.settingsError = data.message || 'Failed to update profile';
                        }
                    } catch (error) {
                        this.settingsError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async changePassword() {
                    this.isLoading = true;
                    this.settingsError = '';
                    this.settingsSuccess = '';

                    if (this.passwordForm.new_password !== this.passwordForm.password_confirmation) {
                        this.settingsError = 'New passwords do not match';
                        this.isLoading = false;
                        return;
                    }

                    if (this.passwordForm.new_password.length < 8) {
                        this.settingsError = 'Password must be at least 8 characters';
                        this.isLoading = false;
                        return;
                    }

                    try {
                        const response = await fetch('/api/auth/change-password', {
                            method: 'POST',
                            headers: this.apiHeaders(),
                            body: JSON.stringify(this.passwordForm),
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.settingsSuccess = 'Password changed successfully!';
                            this.passwordForm = {
                                current_password: '',
                                new_password: '',
                                password_confirmation: '',
                            };
                        } else {
                            this.settingsError = data.message || 'Failed to change password';
                        }
                    } catch (error) {
                        this.settingsError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                handleProfileImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.currentUser.profile_image = e.target.result;
                            this.uploadProfileImage(file);
                        };
                        reader.readAsDataURL(file);
                    }
                },

                async uploadProfileImage(file) {
                    const formData = new FormData();
                    formData.append('profile_image', file);

                    this.isLoading = true;
                    this.settingsError = '';
                    this.settingsSuccess = '';

                    try {
                        const response = await fetch('/api/auth/upload-profile-image', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: formData,
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.currentUser.profile_image = data.image_path;
                            this.settingsSuccess = 'Profile image uploaded successfully!';
                        } else {
                            this.settingsError = data.message || 'Failed to upload profile image';
                        }
                    } catch (error) {
                        this.settingsError = 'Network error: ' + error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                handleLogoUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.logoPreview = e.target.result;
                            this.logoFile = file;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                async uploadLogo() {
                    if (!this.logoFile) {
                        this.settingsError = 'No logo file selected';
                        return;
                    }

                    if (!this.logoForm.name.trim()) {
                        this.settingsError = 'Please enter a logo name';
                        return;
                    }

                    const formData = new FormData();
                    formData.append('logo', this.logoFile);
                    formData.append('name', this.logoForm.name);

                    this.isLoading = true;
                    this.settingsError = '';
                    this.settingsSuccess = '';

                    try {
                        const response = await fetch('/api/auth/upload-logo', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: formData,
                        });

                        const data = await this.readJsonResponse(response);

                        if (data.success) {
                            this.settingsSuccess = 'Logo uploaded successfully!';
                            this.logoPreview = '';
                            this.logoFile = null;
                            this.logoForm.name = '';
                            document.getElementById('logo-upload').value = '';
                            this.loadSystemLogo();
                        } else {
                            this.settingsError = data.message || 'Failed to upload logo';
                        }
                    } catch (error) {
                        this.settingsError = 'Network error: ' + error.message;
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
                    if (this.activeTab === 'settings') return 'Settings';

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
                @include('tabs.settings')
            </div>
        </main>
    </div>
</body>
</html>
