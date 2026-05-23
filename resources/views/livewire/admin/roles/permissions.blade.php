<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Role Permission Management
    </h1>

    <p class="text-sm text-gray-500 mb-6">
        Assign system permissions to roles.
    </p>

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded bg-white p-6 shadow mb-6">
        <label class="block text-sm font-medium mb-1">Select Role</label>

        <select wire:model.live="role_id" class="w-full rounded border-gray-300">
            <option value="">Choose Role</option>

            @foreach ($roles as $role)
                <option value="{{ $role->id }}">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        @error('role_id')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>

    @if ($role_id)
        <div class="rounded bg-white p-6 shadow">
            <h2 class="text-lg font-semibold mb-4">
                Permissions
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($permissions as $permission)
                    <label class="flex items-center gap-2 rounded border p-3">
                        <input
                            type="checkbox"
                            wire:model="selectedPermissions"
                            value="{{ $permission->name }}"
                            class="rounded border-gray-300">

                        <span>{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="mt-6">
                <button
                    wire:click="save"
                    class="rounded bg-blue-600 px-5 py-2 text-white">
                    Save Permissions
                </button>
            </div>
        </div>
    @endif
</div>