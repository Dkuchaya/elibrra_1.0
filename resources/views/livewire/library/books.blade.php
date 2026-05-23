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
                    <button
                        wire:click="openAddToListModal({{ $book->id }})"
                        class="text-sm bg-gray-800 text-white px-3 py-2 rounded-lg">
                        Add to List
                    </button>
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

    @if($addToListModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

            <div class="mb-4 flex justify-between">
                <h2 class="text-xl font-bold">
                    Add Book To List
                </h2>

                <button wire:click="$set('addToListModalOpen', false)" class="text-2xl">
                    ×
                </button>
            </div>

            @if($myLists->count())
                <div>
                    <label class="text-sm font-medium">
                        Select List
                    </label>

                    <select wire:model="selectedListId"
                        class="mt-1 w-full rounded border-gray-300">
                        <option value="">Choose list</option>

                        @foreach($myLists as $list)
                            <option value="{{ $list->id }}">
                                {{ $list->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('selectedListId')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        wire:click="$set('addToListModalOpen', false)"
                        class="rounded bg-gray-200 px-4 py-2">
                        Cancel
                    </button>

                    <button
                        wire:click="addBookToList"
                        class="rounded bg-blue-600 px-4 py-2 text-white">
                        Add Book
                    </button>
                </div>
            @else
                <div class="rounded bg-yellow-100 p-4 text-yellow-800">
                    You do not have any book lists yet.
                </div>

                <div class="mt-4">
                    <a href="{{ route('my-library.index') }}"
                       class="rounded bg-blue-600 px-4 py-2 text-white">
                        Create Book List
                    </a>
                </div>
            @endif

        </div>
    </div>
@endif

</div>