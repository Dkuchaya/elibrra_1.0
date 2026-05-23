<div class="p-6">

    @php
        $permissionGroups = [
            'User Management' => [
                'manage users',
            ],

            'Book Management' => [
                'manage books',
                'manage categories',
                'manage authors',
                'manage publishers',
            ],

            'Subscription Management' => [
                'manage subscriptions',
                'manage subscription plans',
                'manage school subscriptions',
                'manage user subscriptions',
            ],

            'School Management' => [
                'manage schools',
                'manage school admins',
            ],

            'Reports & Analytics' => [
                'view reports',
                'view analytics',
            ],
        ];
    @endphp

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Access Control
        </h1>

        <p class="text-sm text-gray-500">
            Manage role permissions and user permissions.
        </p>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex gap-2">
        <button
            wire:click="$set('activeTab','roles')"
            class="rounded px-4 py-2 font-medium {{ $activeTab === 'roles' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Role Permissions
        </button>

        <button
            wire:click="$set('activeTab','users')"
            class="rounded px-4 py-2 font-medium {{ $activeTab === 'users' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            User Permissions
        </button>
    </div>

    @if($activeTab === 'roles')
        <div class="rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-semibold">
                Role Permissions
            </h2>

            <div class="mb-6">
                <label class="mb-1 block text-sm font-medium">
                    Select Role
                </label>

                <select wire:model.live="role_id" class="w-full rounded border-gray-300">
                    <option value="">Choose Role</option>

                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>

                @error('role_id')
                    <span class="text-sm text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            @if($role_id)
                @foreach($permissionGroups as $group => $groupPermissions)
                    <div class="mb-6">
                        <h3 class="mb-3 border-b pb-2 text-lg font-semibold text-gray-700">
                            {{ $group }}
                        </h3>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                            @forelse($permissions->whereIn('name', $groupPermissions) as $permission)
                                <label class="flex items-center gap-3 rounded border p-3 hover:bg-gray-50">
                                    <input
                                        type="checkbox"
                                        wire:model="selectedRolePermissions"
                                        value="{{ $permission->name }}"
                                        class="rounded border-gray-300">

                                    <span>{{ $permission->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-400">
                                    No permissions found in this group.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    <button
                        wire:click="saveRolePermissions"
                        class="rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">
                        Save Role Permissions
                    </button>
                </div>
            @endif
        </div>
    @endif

    @if($activeTab === 'users')
        <div class="rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-lg font-semibold">
                User Permissions
            </h2>

            <div class="mb-6">
                <label class="mb-1 block text-sm font-medium">
                    Select User
                </label>

                <select wire:model.live="user_id" class="w-full rounded border-gray-300">
                    <option value="">Choose User</option>

                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>

                @error('user_id')
                    <span class="text-sm text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            @if($user_id)
                @foreach($permissionGroups as $group => $groupPermissions)
                    <div class="mb-6">
                        <h3 class="mb-3 border-b pb-2 text-lg font-semibold text-gray-700">
                            {{ $group }}
                        </h3>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                            @forelse($permissions->whereIn('name', $groupPermissions) as $permission)
                                <label class="flex items-center gap-3 rounded border p-3 hover:bg-gray-50">
                                    <input
                                        type="checkbox"
                                        wire:model="selectedUserPermissions"
                                        value="{{ $permission->name }}"
                                        class="rounded border-gray-300">

                                    <span>{{ $permission->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-400">
                                    No permissions found in this group.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    <button
                        wire:click="saveUserPermissions"
                        class="rounded bg-green-600 px-5 py-2 text-white hover:bg-green-700">
                        Save User Permissions
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>