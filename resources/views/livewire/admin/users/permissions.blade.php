<div class="p-6">
    <h1 class="mb-2 text-2xl font-bold text-gray-800">
        User Permission Management
    </h1>

    <p class="mb-6 text-sm text-gray-500">
        Assign extra permissions directly to individual users.
    </p>

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded bg-white p-6 shadow">
        <label class="mb-1 block text-sm font-medium">Select User</label>

        <select wire:model.live="user_id" class="w-full rounded border-gray-300">
            <option value="">Choose User</option>

            @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }} - {{ $user->email }}
                    @if($user->roles->count())
                        ({{ $user->roles->pluck('name')->join(', ') }})
                    @endif
                </option>
            @endforeach
        </select>

        @error('user_id')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>

    @if ($user_id)
        <div class="rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-semibold">
                Direct Permissions
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
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
                    Save User Permissions
                </button>
            </div>
        </div>
    @endif
</div>