<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Dashboard
                </h1>

                <p class="text-gray-500 mt-1">
                    eLibrra analytics and system overview.
                </p>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Schools</p>

                    <h2 class="mt-2 text-3xl font-bold text-gray-800">
                        {{ number_format($totalSchools) }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Users</p>

                    <h2 class="mt-2 text-3xl font-bold text-gray-800">
                        {{ number_format($totalUsers) }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Books</p>

                    <h2 class="mt-2 text-3xl font-bold text-gray-800">
                        {{ number_format($totalBooks) }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Categories</p>

                    <h2 class="mt-2 text-3xl font-bold text-gray-800">
                        {{ number_format($totalCategories) }}
                    </h2>
                </div>

            </div>

            {{-- Reading Analytics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">
                        Books Read Today
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-blue-600">
                        {{ number_format($booksReadToday) }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">
                        Books Read This Week
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-green-600">
                        {{ number_format($booksReadThisWeek) }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">
                        Books Read This Month
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-purple-600">
                        {{ number_format($booksReadThisMonth) }}
                    </h2>
                </div>

            </div>

            {{-- Most Read Books + Categories --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Most Read Books --}}
                <div class="bg-white rounded-xl shadow-sm border">

                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Most Read Books
                        </h2>
                    </div>

                    <div class="divide-y">

                        @forelse($mostReadBooks as $book)

                            <div class="flex items-center justify-between px-6 py-4">

                                <div>
                                    <h3 class="font-medium text-gray-800">
                                        {{ $book->title }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        {{ $book->authors->pluck('name')->join(', ') }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">
                                        {{ number_format($book->views) }} Reads
                                    </span>
                                </div>

                            </div>

                        @empty

                            <div class="px-6 py-6 text-center text-gray-500">
                                No reading activity yet.
                            </div>

                        @endforelse

                    </div>

                </div>

                {{-- Top Categories --}}
                <div class="bg-white rounded-xl shadow-sm border">

                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Top Categories
                        </h2>
                    </div>

                    <div class="divide-y">

                        @forelse($topCategories as $category)

                            <div class="flex items-center justify-between px-6 py-4">

                                <div>
                                    <h3 class="font-medium text-gray-800">
                                        {{ $category->name }}
                                    </h3>
                                </div>

                                <div>
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                                        {{ $category->books_count }} Books
                                    </span>
                                </div>

                            </div>

                        @empty

                            <div class="px-6 py-6 text-center text-gray-500">
                                No categories found.
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>