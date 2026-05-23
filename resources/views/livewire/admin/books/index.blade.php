<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Book Management</h1>
            <p class="text-sm text-gray-500">Manage global eLibrary books.</p>
        </div>

        <button wire:click="openModal"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Book
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-100 border border-red-300 p-4">
        <div class="font-semibold text-red-700 mb-2">
            Please fix the following errors:
        </div>

        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <input type="text"
        wire:model.live="search"
        placeholder="Search by title, ISBN or legacy ISBN..."
        class="w-full mb-4 border-gray-300 rounded-lg shadow-sm">

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Book</th>
                    <th class="px-4 py-3 text-left">Authors</th>
                    <th class="px-4 py-3 text-left">Publisher</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Access</th>
                    <th class="px-4 py-3 text-left">Views</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($books as $book)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                                         class="w-12 h-14 object-cover rounded">
                                @else
                                    <div class="w-12 h-14 bg-gray-200 rounded flex items-center justify-center text-xs">
                                        No Cover
                                    </div>
                                @endif

                                <div>
                                    <div class="font-semibold">{{ $book->title }}</div>
                                    <div class="text-xs text-gray-500">ISBN: {{ $book->isbn ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">Year: {{ $book->published_year ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            {{ $book->authors->pluck('name')->join(', ') ?: 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $book->publisher?->name ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $book->category?->name ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3 uppercase">
                            {{ $book->book_type }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($book->subscription_required)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                    Subscription
                                </span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                    Free
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ number_format($book->views) }}
                        </td>

                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $book->id }})">
                                @if ($book->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                        Inactive
                                    </span>
                                @endif
                            </button>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $book->id }})"
                                class="text-blue-600 hover:underline">
                                Edit
                            </button>

                            <button wire:click="delete({{ $book->id }})"
                                onclick="return confirm('Delete this book?')"
                                class="text-red-600 hover:underline ml-3">
                                Delete
                            </button>
                            <a href="{{ route('library.books.read', $book->slug) }}"
                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Read Book
                        </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                            No books found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>

    @if ($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-5xl max-h-[90vh] overflow-y-auto p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">
                        {{ $book_id ? 'Edit Book' : 'Add Book' }}
                    </h2>

                    <button wire:click="$set('modalOpen', false)"
                        class="text-gray-500 hover:text-red-600 text-2xl">
                        ×
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                     <div>
                        <label class="text-sm font-medium">Title</label>

                        <input type="text"
                            wire:model="title"
                            class="w-full rounded-lg border @error('title') border-red-500 @else border-gray-300 @enderror">

                        @error('title')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">ISBN</label>

                        <input type="text"
                            wire:model="isbn"
                            class="w-full rounded-lg border @error('isbn') border-red-500 @else border-gray-300 @enderror">

                        @error('isbn')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Legacy ISBN</label>
                        <input type="text" wire:model="legacy_isbn" class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Authors</label>

                        <select wire:model="author_ids" multiple
                            class="w-full rounded-lg border h-28 @error('author_ids') border-red-500 @else border-gray-300 @enderror">

                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}">
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('author_ids')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Publisher</label>
                        <select wire:model="publisher_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Select Publisher</option>
                            @foreach ($publishers as $publisher)
                                <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Category</label>
                        <select wire:model="book_category_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                   <div>
                        <label class="text-sm font-medium">Published Year</label>

                        <input type="number"
                            wire:model="published_year"
                            class="w-full rounded-lg border @error('published_year') border-red-500 @else border-gray-300 @enderror">

                        @error('published_year')
                            <span class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Pages</label>
                        <input type="number" wire:model="pages" class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Language</label>
                        <input type="text" wire:model="language" class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Edition</label>
                        <input type="text" wire:model="edition" class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Book Type</label>
                        <select wire:model="book_type" class="w-full border-gray-300 rounded-lg">
                            <option value="pdf">PDF</option>
                            <option value="epub">EPUB</option>
                            <option value="audio">Audio</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Visibility</label>
                        <select wire:model="visibility" class="w-full border-gray-300 rounded-lg">
                            <option value="public">Public</option>
                            <option value="school">School</option>
                            <option value="restricted">Restricted</option>
                        </select>
                    </div>

                    <div>
                            <label class="text-sm font-medium">Cover Image</label>

                            <input type="file"
                                wire:model="new_cover_image"
                                class="w-full rounded-lg border p-2 @error('new_cover_image') border-red-500 @else border-gray-300 @enderror">

                            @error('new_cover_image')
                                <span class="text-red-500 text-xs">
                                    {{ $message }}
                                </span>
                            @enderror

                            <div wire:loading wire:target="new_cover_image"
                                class="text-blue-500 text-xs mt-1">
                                Uploading image...
                            </div>
                        </div>

                    <div>
    <label class="text-sm font-medium">Book File</label>

                    <input type="file"
                            wire:model="new_pdf_file"
                            class="w-full rounded-lg border p-2 @error('new_pdf_file') border-red-500 @else border-gray-300 @enderror">

                        @error('new_pdf_file')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror

                        <div wire:loading wire:target="new_pdf_file"
                            class="text-blue-500 text-xs mt-1">
                            Uploading file...
                        </div>
                    </div>

                    <div class="flex items-center gap-6 mt-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="featured">
                            Featured
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="subscription_required">
                            Subscription Required
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="is_active">
                            Active
                        </label>
                    </div>
                </div>

               <div class="mt-4">
                    <label class="text-sm font-medium">Description</label>

                    <textarea wire:model="description"
                        rows="4"
                        class="w-full rounded-lg border @error('description') border-red-500 @else border-gray-300 @enderror"></textarea>

                    @error('description')
                        <span class="text-red-500 text-xs">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('modalOpen', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                        Cancel
                    </button>

                    <button wire:click="save"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Save Book
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>