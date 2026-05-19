<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">School Management</h1>
            <p class="text-sm text-gray-500">Create, update, activate, and manage schools.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded bg-white p-6 shadow">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">
            {{ $isEditing ? 'Edit School' : 'Add New School' }}
        </h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">School Name</label>
                <input
                    type="text"
                    wire:model="name"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter school name"
                >
                @error('name')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    wire:model="email"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="school@example.com"
                >
                @error('email')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Phone</label>
                <input
                    type="text"
                    wire:model="phone"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="0970000000"
                >
                @error('phone')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                <select
                    wire:model="is_active"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700">Address</label>
                <textarea
                    wire:model="address"
                    rows="3"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter school address"
                ></textarea>
                @error('address')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        School Logo
                    </label>

                    <input
                        type="file"
                        wire:model="logo"
                        accept="image/*"
                        class="w-full rounded border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                    @error('logo')
                        <span class="mt-1 block text-sm text-red-600">
                            {{ $message }}
                        </span>
                    @enderror

                    {{-- Preview New Upload --}}
                    @if ($logo)
                        <div class="mt-3">
                            <img
                                src="{{ $logo->temporaryUrl() }}"
                                class="h-24 w-24 rounded-lg border object-cover shadow"
                            >
                        </div>
                    @endif

                    {{-- Existing Logo --}}
                    @if (!$logo && $existingLogo)
                        <div class="mt-3">
                            <img
                                src="{{ asset('storage/' . $existingLogo) }}"
                                class="h-24 w-24 rounded-lg border object-cover shadow"
                            >
                        </div>
                    @endif
                </div>
        </div>

        <div class="mt-5 flex gap-2">
            @if ($isEditing)
                <button
                    type="button"
                    wire:click="update"
                    wire:loading.attr="disabled"
                    class="rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    Update School
                </button>

                <button
                    type="button"
                    wire:click="resetForm"
                    class="rounded bg-gray-500 px-5 py-2 text-white hover:bg-gray-600"
                >
                    Cancel
                </button>
            @else
                <button
                    type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    class="rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    Save School
                </button>
            @endif

            <span wire:loading class="text-sm text-gray-500">
                Processing...
            </span>
        </div>
    </div>

    <div class="rounded bg-white p-6 shadow">
        <div class="mb-4 flex items-center justify-between">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by name, email, or phone..."
                class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 md:w-1/3"
            >
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 text-left">School</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Phone</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Created</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($schools as $school)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-800">
                                {{ $school->name }}
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $school->email ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $school->phone ?? '-' }}
                            </td>

                            <td class="p-3">
                                @if ($school->is_active)
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $school->created_at?->format('d M Y') }}
                            </td>

                            <td class="p-3">
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $school->id }})"
                                        class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="toggleStatus({{ $school->id }})"
                                        class="rounded bg-indigo-600 px-3 py-1 text-white hover:bg-indigo-700"
                                    >
                                        {{ $school->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $school->id }})"
                                        class="rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">
                                No schools found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $schools->links() }}
        </div>
    </div>
</div>