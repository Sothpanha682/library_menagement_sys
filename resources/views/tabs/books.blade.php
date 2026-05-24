<!-- ========================================== -->
<!-- BOOKS TAB -->
<!-- ========================================== -->
<div x-show="activeTab === 'books'">
    @include('tabs.books.list')
    @include('tabs.books.view')
    @include('tabs.books.form')
    @include('tabs.books.delete-modal')
</div>
