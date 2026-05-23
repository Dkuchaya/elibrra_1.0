<div class="min-h-screen bg-gray-100 p-6">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Library</h1>
            <p class="text-sm text-gray-500">
                Create and share book lists like playlists.
            </p>
        </div>

        <button
            wire:click="openModal"
            class="rounded bg-blue-600 px-4 py-2 text-white">
            Create Book List
        </button>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3 xl:grid-cols-4">
        @forelse($lists as $list)
            <div class="rounded-xl bg-white p-5 shadow">
                <div class="mb-3 flex justify-between">
                    <h2 class="text-lg font-bold text-gray-800">
                        {{ $list->name }}
                    </h2>

                    <span class="rounded bg-gray-100 px-2 py-1 text-xs capitalize text-gray-600">
                        {{ $list->visibility }}
                    </span>
                </div>

                <p class="mb-4 text-sm text-gray-500">
                    {{ $list->description ?: 'No description.' }}
                </p>

                <p class="mb-4 text-sm text-gray-600">
                    {{ $list->books_count }} books
                </p>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('book-lists.show', $list->slug) }}"
                       class="rounded bg-blue-600 px-3 py-2 text-sm text-white">
                        Open
                    </a>

                    <button wire:click="edit({{ $list->id }})"
                        class="rounded bg-yellow-500 px-3 py-2 text-sm text-white">
                        Edit
                    </button>

                    <button wire:click="delete({{ $list->id }})"
                        onclick="return confirm('Delete this list?')"
                        class="rounded bg-red-600 px-3 py-2 text-sm text-white">
                        Delete
                    </button>

                    @if($list->visibility !== 'private')
                        <button
                            type="button"
                            onclick="navigator.clipboard.writeText('{{ route('book-lists.show', $list->slug) }}'); alert('Share link copied!')"
                            class="rounded bg-gray-700 px-3 py-2 text-sm text-white">
                            Share
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-4 rounded bg-white p-8 text-center text-gray-500">
                You have not created any book lists yet.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $lists->links() }}
    </div>

    @if($modalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-xl rounded-xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex justify-between">
                    <h2 class="text-xl font-bold">
                        {{ $isEditing ? 'Edit Book List' : 'Create Book List' }}
                    </h2>

                    <button wire:click="$set('modalOpen', false)" class="text-2xl">
                        ×
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded border-gray-300">
                        @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Description</label>
                        <textarea wire:model="description" rows="4"
                            class="w-full rounded border-gray-300"></textarea>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Visibility</label>
                        <select wire:model="visibility"
                            class="w-full rounded border-gray-300">
                            <option value="private">Private</option>
                            <option value="public">Public</option>
                            <option value="unlisted">Unlisted / Share Link</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('modalOpen', false)"
                        class="rounded bg-gray-200 px-4 py-2">
                        Cancel
                    </button>

                    <button wire:click="save"
                        class="rounded bg-blue-600 px-4 py-2 text-white">
                        Save List
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>