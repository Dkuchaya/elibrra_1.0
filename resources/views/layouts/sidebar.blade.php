<aside class="w-64 bg-gray-900 text-white min-h-screen fixed left-0 top-0">

    <div class="p-6 text-2xl font-bold border-b border-gray-700">
        eLibrary
    </div>

    <nav class="mt-6 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="block px-6 py-3 hover:bg-gray-800">
            Dashboard
        </a>

        @role('Super Admin')
            <a href="#" class="block px-6 py-3 hover:bg-gray-800">
                Schools
            </a>

            <a href="#" class="block px-6 py-3 hover:bg-gray-800">
                Subscriptions
            </a>
        @endrole

        @can('manage users')
            <a href="#" class="block px-6 py-3 hover:bg-gray-800">
                Users
            </a>
        @endcan

        @can('manage books')
            <a href="{{ route('admin.books.index') }}" 
            class="block px-6 py-3 hover:bg-gray-800">
                Books
            </a>
        @endcan

       
        @can('manage authors')
            <a href="{{ route('authors.index') }}"
            class="block px-6 py-3 hover:bg-gray-800">
                Authors
            </a>
        @endcan

       

        @can('manage publishers')
            <a href="{{ route('publishers.index') }}"
            class="block px-6 py-3 hover:bg-gray-800">
                Publishers
            </a>

        @endcan
       
        @can('manage categories')
            <a href="{{ route('book-categories.index') }}"
            class="block px-6 py-3 hover:bg-gray-800">
                Categories
            </a>
        @endcan

        <a href="#" class="block px-6 py-3 hover:bg-gray-800">
            My Library
        </a>

        <a href="{{ route('users.index') }}"
        class="block px-6 py-3 hover:bg-gray-800">
            Users/Admins
        </a>

    </nav>
</aside>