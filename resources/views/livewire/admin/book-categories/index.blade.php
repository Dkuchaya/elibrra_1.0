<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Book Categories</h1>
        <p class="text-sm text-gray-500">Manage categories used to organize books.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded bg-white p-6 shadow">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $isEditing ? 'Edit Category' : 'Add New Category' }}
        </h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Category Name</label>
                <input type="text" wire:model="name" class="w-full rounded border-gray-300">
                @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Status</label>
                <select wire:model="is_active" class="w-full rounded border-gray-300">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Description</label>
                <textarea wire:model="description" rows="3" class="w-full rounded border-gray-300"></textarea>
            </div>
        </div>

        <div class="mt-5 flex gap-2">
            @if ($isEditing)
                <button type="button" wire:click="update" class="rounded bg-blue-600 px-5 py-2 text-white">
                    Update Category
                </button>

                <button type="button" wire:click="resetForm" class="rounded bg-gray-500 px-5 py-2 text-white">
                    Cancel
                </button>
            @else
                <button type="button" wire:click="save" class="rounded bg-blue-600 px-5 py-2 text-white">
                    Save Category
                </button>
            @endif
        </div>
    </div>

    <div class="rounded bg-white p-6 shadow">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search categories..."
            class="mb-4 w-full rounded border-gray-300 md:w-1/3"
        >

        <div class="overflow-x-auto">
            <table class="w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-t">
                            <td class="p-3 font-medium">{{ $category->name }}</td>
                            <td class="p-3">{{ $category->description ?? '-' }}</td>
                            <td class="p-3">
                                @if ($category->is_active)
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">Active</span>
                                @else
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex gap-2">
                                    <button type="button" wire:click="edit({{ $category->id }})" class="rounded bg-yellow-500 px-3 py-1 text-white">
                                        Edit
                                    </button>

                                    <button type="button" wire:click="toggleStatus({{ $category->id }})" class="rounded bg-indigo-600 px-3 py-1 text-white">
                                        {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>

                                    <button type="button" wire:click="confirmDelete({{ $category->id }})" class="rounded bg-red-600 px-3 py-1 text-white">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>