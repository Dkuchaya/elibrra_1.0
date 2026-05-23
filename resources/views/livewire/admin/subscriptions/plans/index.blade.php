<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Subscription Plans</h1>
            <p class="text-sm text-gray-500">Manage school and individual subscription plans.</p>
        </div>

        <button wire:click="openModal"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Plan
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <input type="text"
        wire:model.live="search"
        placeholder="Search plans..."
        class="w-full mb-4 border-gray-300 rounded-lg shadow-sm">

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Duration</th>
                    <th class="px-4 py-3 text-left">Grace</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($plans as $plan)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">
                            {{ $plan->name }}
                        </td>

                        <td class="px-4 py-3 capitalize">
                            {{ $plan->type }}
                        </td>

                        <td class="px-4 py-3">
                            K{{ number_format($plan->price, 2) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $plan->duration_days }} days
                        </td>

                        <td class="px-4 py-3">
                            {{ $plan->grace_period_days }} days
                        </td>

                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $plan->id }})">
                                @if ($plan->is_active)
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
                            <button wire:click="edit({{ $plan->id }})"
                                class="text-blue-600 hover:underline">
                                Edit
                            </button>

                            <button wire:click="delete({{ $plan->id }})"
                                onclick="return confirm('Delete this plan?')"
                                class="text-red-600 hover:underline ml-3">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No subscription plans found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $plans->links() }}
    </div>

    @if ($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">
                        {{ $plan_id ? 'Edit Plan' : 'Add Plan' }}
                    </h2>

                    <button wire:click="$set('modalOpen', false)"
                        class="text-gray-500 hover:text-red-600 text-2xl">
                        ×
                    </button>
                </div>

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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium">Plan Name</label>
                        <input type="text"
                            wire:model="name"
                            class="w-full rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Plan Type</label>
                        <select wire:model="type"
                            class="w-full rounded-lg border @error('type') border-red-500 @else border-gray-300 @enderror">
                            <option value="school">School</option>
                            <option value="individual">Individual</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Price</label>
                        <input type="number"
                            step="0.01"
                            wire:model="price"
                            class="w-full rounded-lg border @error('price') border-red-500 @else border-gray-300 @enderror">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Duration Days</label>
                        <input type="number"
                            wire:model="duration_days"
                            class="w-full rounded-lg border @error('duration_days') border-red-500 @else border-gray-300 @enderror">
                        @error('duration_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Grace Period Days</label>
                        <input type="number"
                            wire:model="grace_period_days"
                            class="w-full rounded-lg border @error('grace_period_days') border-red-500 @else border-gray-300 @enderror">
                        @error('grace_period_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center mt-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="is_active">
                            Active
                        </label>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('modalOpen', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                        Cancel
                    </button>

                    <button wire:click="save"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Save Plan
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>