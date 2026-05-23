<div class="min-h-screen bg-gray-100 p-6">

    <div class="mb-6 rounded bg-white p-6 shadow">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $list->name }}
                </h1>

                <p class="text-sm text-gray-500">
                    By {{ $list->user?->name }} · {{ $list->visibility }}
                </p>

                <p class="mt-2 text-gray-600">
                    {{ $list->description }}
                </p>
            </div>

            @if($list->visibility !== 'private')
                <button
                    onclick="navigator.clipboard.writeText('{{ route('book-lists.show', $list->slug) }}'); alert('Share link copied!')"
                    class="rounded bg-gray-800 px-4 py-2 text-white">
                    Copy Share Link
                </button>
            @endif
        </div>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3 xl:grid-cols-4">
        @forelse($list->books as $book)
            <div class="overflow-hidden rounded-xl bg-white shadow">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                         class="h-72 w-full object-cover">
                @else
                    <div class="flex h-72 items-center justify-center bg-gray-200 text-gray-400">
                        No Cover
                    </div>
                @endif

                <div class="p-4">
                    <p class="text-xs text-gray-500">
                        {{ $book->category?->name ?? 'General' }}
                    </p>

                    <h2 class="mt-1 font-bold text-gray-800">
                        {{ $book->title }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}
                    </p>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('library.books.read', $book->slug) }}"
                           class="rounded bg-blue-600 px-3 py-2 text-sm text-white">
                            Read
                        </a>

                        @if($list->user_id === auth()->id())
                            <button wire:click="removeBook({{ $book->id }})"
                                class="rounded bg-red-600 px-3 py-2 text-sm text-white">
                                Remove
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 rounded bg-white p-8 text-center text-gray-500">
                No books in this list yet.
            </div>
        @endforelse
    </div>
</div>