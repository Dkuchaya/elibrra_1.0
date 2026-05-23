<div class="min-h-screen bg-white p-6">

    <div class="flex flex-wrap gap-4 mb-8 items-center">
        <div class="flex">
            <input type="text"
                wire:model.live="search"
                placeholder="Search books..."
                class="border-gray-300 rounded-l-lg w-72">

            <button class="bg-gray-800 text-white px-5 rounded-r-lg">
                🔍
            </button>
        </div>

        <select wire:model.live="sort" class="border-gray-300 rounded-lg">
            <option value="title">-- Sort by Title --</option>
            <option value="latest">-- Latest Books --</option>
            <option value="popular">-- Most Viewed --</option>
        </select>

        <select wire:model.live="category" class="border-gray-300 rounded-lg">
            <option value="">-- Filter by Category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse ($books as $book)
            <div class="border rounded-xl bg-white overflow-hidden hover:shadow-lg transition relative">

                <button class="absolute top-3 right-3 text-2xl text-gray-800 z-10">
                    ♡
                </button>

                <a href="{{ route('library.books.read', $book->slug) }}">
                    @if ($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}"
                            class="w-full h-72 object-cover">
                    @else
                        <div class="w-full h-72 bg-gray-100 flex items-center justify-center text-gray-400">
                            No Cover
                        </div>
                    @endif
                </a>

                <div class="p-5">
                    <p class="text-gray-700 mb-1">
                        {{ $book->category?->name ?? 'General' }}
                    </p>

                    <h3 class="font-bold text-lg leading-snug mb-3">
                        {{ $book->title }}
                    </h3>

                    <p class="text-gray-700 leading-7">
                        {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}
                    </p>

                    <div class="mt-4 flex justify-between items-center">
                        @if ($book->subscription_required)
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                                Premium
                            </span>
                        @else
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                                Free
                            </span>
                        @endif

                        <a href="{{ route('library.books.read', $book->slug) }}"
                           class="text-sm bg-blue-600 text-white px-3 py-2 rounded-lg">
                            Read
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-500 py-12">
                No books found.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $books->links() }}
    </div>

</div>