<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">School Subscriptions</h1>
            <p class="text-sm text-gray-500">Manage school access to premium eLibrary books.</p>
        </div>

        <button wire:click="openModal"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add School Subscription
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <input type="text"
        wire:model.live="search"
        placeholder="Search by school or plan..."
        class="w-full mb-4 border-gray-300 rounded-lg shadow-sm">

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">School</th>
                    <th class="px-4 py-3 text-left">Plan</th>
                    <th class="px-4 py-3 text-left">Period</th>
                    <th class="px-4 py-3 text-left">Grace</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                    <th class="px-4 py-3 text-left">Reference</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($subscriptions as $subscription)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">
                            {{ $subscription->school?->name ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->plan?->name ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->starts_at?->format('d M Y') }}
                            -
                            {{ $subscription->expires_at?->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->grace_period_days }} days
                        </td>

                        <td class="px-4 py-3">
                            K{{ number_format($subscription->amount_paid, 2) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->payment_reference ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($subscription->status === 'active')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Active</span>
                            @elseif ($subscription->status === 'expired')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Expired</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Cancelled</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $subscription->id }})"
                                class="text-blue-600 hover:underline">
                                Edit
                            </button>

                            <button wire:click="cancel({{ $subscription->id }})"
                                onclick="return confirm('Cancel this subscription?')"
                                class="text-red-600 hover:underline ml-3">
                                Cancel
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            No school subscriptions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>

    @if ($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">
                        {{ $subscription_id ? 'Edit School Subscription' : 'Add School Subscription' }}
                    </h2>

                    <button wire:click="$set('modalOpen', false)"
                        class="text-gray-500 hover:text-red-600 text-2xl">
                        ×
                    </button>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 border border-red-300 p-4">
                        <div class="font-semibold text-red-700 mb-2">Please fix the following errors:</div>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">School</label>
                        <select wire:model="school_id"
                            class="w-full rounded-lg border @error('school_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="">Select School</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('school_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Plan</label>
                        <select wire:model.live="subscription_plan_id"
                            class="w-full rounded-lg border @error('subscription_plan_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="">Select Plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} - K{{ number_format($plan->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_plan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Starts At</label>
                        <input type="date" wire:model.live="starts_at"
                            class="w-full rounded-lg border @error('starts_at') border-red-500 @else border-gray-300 @enderror">
                        @error('starts_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Expires At</label>
                        <input type="date" wire:model="expires_at"
                            class="w-full rounded-lg border @error('expires_at') border-red-500 @else border-gray-300 @enderror">
                        @error('expires_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Grace Period Days</label>
                        <input type="number" wire:model="grace_period_days"
                            class="w-full rounded-lg border @error('grace_period_days') border-red-500 @else border-gray-300 @enderror">
                        @error('grace_period_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Amount Paid</label>
                        <input type="number" step="0.01" wire:model="amount_paid"
                            class="w-full rounded-lg border @error('amount_paid') border-red-500 @else border-gray-300 @enderror">
                        @error('amount_paid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Payment Reference</label>
                        <input type="text" wire:model="payment_reference"
                            placeholder="Airtel/MTN/Bank/Receipt No."
                            class="w-full rounded-lg border @error('payment_reference') border-red-500 @else border-gray-300 @enderror">
                        @error('payment_reference') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Paid At</label>
                        <input type="date" wire:model="paid_at"
                            class="w-full rounded-lg border @error('paid_at') border-red-500 @else border-gray-300 @enderror">
                        @error('paid_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Status</label>
                        <select wire:model="status"
                            class="w-full rounded-lg border @error('status') border-red-500 @else border-gray-300 @enderror">
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('modalOpen', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                        Cancel
                    </button>

                    <button wire:click="save"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Save Subscription
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>