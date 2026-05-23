<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User/Admin Management</h1>
        <p class="text-sm text-gray-500">Create school admins, librarians, students, and readers.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded bg-white p-6 shadow">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $isEditing ? 'Edit User' : 'Add New User/Admin' }}
        </h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @role('Super Admin')
                <div>
                    <label class="mb-1 block text-sm font-medium">School</label>
                    <select wire:model="school_id" class="w-full rounded border-gray-300">
                        <option value="">No School / Individual User</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
            @endrole

            <div>
                <label class="mb-1 block text-sm font-medium">Role</label>
                <select wire:model="role" class="w-full rounded border-gray-300">
                    <option value="">Select Role</option>
                    @foreach ($roles as $roleOption)
                        <option value="{{ $roleOption->name }}">{{ $roleOption->name }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">First Name</label>
                <input type="text" wire:model="first_name" class="w-full rounded border-gray-300">
                @error('first_name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Last Name</label>
                <input type="text" wire:model="last_name" class="w-full rounded border-gray-300">
                @error('last_name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input type="email" wire:model="email" class="w-full rounded border-gray-300">
                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Phone</label>
                <input type="text" wire:model="phone" class="w-full rounded border-gray-300">
                @error('phone') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Gender</label>
                <select wire:model="gender" class="w-full rounded border-gray-300">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">City</label>
                <input type="text" wire:model="city" class="w-full rounded border-gray-300">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Status</label>
                <select wire:model="is_active" class="w-full rounded border-gray-300">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="mt-5 flex gap-2">
            @if ($isEditing)
                <button type="button" wire:click="update" class="rounded bg-blue-600 px-5 py-2 text-white">
                    Update User
                </button>

                <button type="button" wire:click="resetForm" class="rounded bg-gray-500 px-5 py-2 text-white">
                    Cancel
                </button>
            @else
                <button type="button" wire:click="save" class="rounded bg-blue-600 px-5 py-2 text-white">
                    Save User
                </button>
            @endif
        </div>
    </div>

    

    <div class="rounded bg-white p-6 shadow">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search users..."
            class="mb-4 w-full rounded border-gray-300 md:w-1/3"
        >
    <a href="{{ asset('templates/users_import_template.csv') }}"
    download
    class="rounded bg-blue-600 px-4 py-2 text-white">
        Download CSV Template
    </a>
    <div class="mb-6 rounded bg-white p-6 shadow">
    <h2 class="mb-4 text-lg font-semibold">Bulk Upload Users</h2>

    <div class="flex flex-col gap-3 md:flex-row md:items-center">
        <input
            type="file"
            wire:model="csv_file"
            accept=".csv,.txt"
            class="rounded border border-gray-300 p-2"
        >

        <button
            type="button"
            wire:click="importCsv"
            class="rounded bg-green-600 px-5 py-2 text-white">
            Upload CSV
        </button>
    </div>

    @error('csv_file')
        <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
    @enderror

    <div wire:loading wire:target="csv_file,importCsv" class="mt-2 text-sm text-blue-600">
        Processing CSV...
    </div>

    <div class="mt-4 text-sm text-gray-600">
        CSV format:
        <code>first_name,last_name,email,phone,gender,city,role,school_id,password</code>
    </div>

    @if(! auth()->user()->hasRole('Super Admin'))
        <div class="mt-2 text-sm text-blue-700">
            School Admin uploads will automatically be assigned to your school and role Reader.
        </div>
    @endif
</div>

        <div class="overflow-x-auto">
            <table class="w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Phone</th>
                        <th class="p-3 text-left">School</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-t">
                            <td class="p-3">{{ $user->name }}</td>
                            <td class="p-3">{{ $user->email }}</td>
                            <td class="p-3">{{ $user->phone ?? '-' }}</td>
                            <td class="p-3">{{ $user->school?->name ?? 'Individual' }}</td>
                            <td class="p-3">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="p-3">
                                @if ($user->is_active)
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">Active</span>
                                @else
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex gap-2">
                                    <button type="button" wire:click="edit({{ $user->id }})" class="rounded bg-yellow-500 px-3 py-1 text-white">
                                        Edit
                                    </button>

                                    <button type="button" wire:click="toggleStatus({{ $user->id }})" class="rounded bg-indigo-600 px-3 py-1 text-white">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>

                                    <button type="button" wire:click="confirmDelete({{ $user->id }})" class="rounded bg-red-600 px-3 py-1 text-white">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>