<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                User Subscriptions
            </h1>

            <p class="text-sm text-gray-500">
                Manage individual user subscriptions.
            </p>
        </div>

        <button
            wire:click="openModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add Subscription
        </button>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search user..."
            class="w-full border-gray-300 rounded-lg shadow-sm">
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Plan</th>
                    <th class="px-4 py-3 text-left">Period</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                    <th class="px-4 py-3 text-left">Reference</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($subscriptions as $subscription)

                    <tr class="border-t">

                        <td class="px-4 py-3">
                            {{ $subscription->user?->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->plan?->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->starts_at?->format('d M Y') }}
                            -
                            {{ $subscription->expires_at?->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3">
                            K{{ number_format($subscription->amount_paid,2) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $subscription->payment_reference }}
                        </td>

                        <td class="px-4 py-3">

                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                    Active
                                </span>
                            @elseif($subscription->status === 'expired')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                    Expired
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                    Cancelled
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3 text-right">

                            <button
                                wire:click="edit({{ $subscription->id }})"
                                class="text-blue-600 hover:underline">
                                Edit
                            </button>

                            <button
                                wire:click="cancel({{ $subscription->id }})"
                                onclick="return confirm('Cancel this subscription?')"
                                class="text-red-600 hover:underline ml-3">
                                Cancel
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="px-4 py-6 text-center text-gray-500">
                            No subscriptions found.
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>

    @if($modalOpen)

        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

            <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">
                        {{ $subscription_id ? 'Edit Subscription' : 'Add Subscription' }}
                    </h2>

                    <button
                        wire:click="$set('modalOpen', false)"
                        class="text-gray-500 hover:text-red-600 text-2xl">
                        ×
                    </button>
                </div>

                @if($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 border border-red-300 p-4">

                        <div class="font-semibold text-red-700 mb-2">
                            Please fix the following errors:
                        </div>

                        <ul class="list-disc list-inside text-red-600 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label>User</label>

                        <select
                            wire:model="user_id"
                            class="w-full border-gray-300 rounded-lg">

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                    ({{ $user->email }})
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label>Plan</label>

                        <select
                            wire:model.live="subscription_plan_id"
                            class="w-full border-gray-300 rounded-lg">

                            <option value="">
                                Select Plan
                            </option>

                            @foreach($plans as $plan)

                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }}
                                    - K{{ number_format($plan->price,2) }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label>Start Date</label>

                        <input
                            type="date"
                            wire:model.live="starts_at"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label>Expiry Date</label>

                        <input
                            type="date"
                            wire:model="expires_at"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label>Amount Paid</label>

                        <input
                            type="number"
                            step="0.01"
                            wire:model="amount_paid"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label>Payment Reference</label>

                        <input
                            type="text"
                            wire:model="payment_reference"
                            placeholder="Receipt No / Airtel / MTN"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label>Paid At</label>

                        <input
                            type="date"
                            wire:model="paid_at"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label>Status</label>

                        <select
                            wire:model="status"
                            class="w-full border-gray-300 rounded-lg">

                            <option value="active">
                                Active
                            </option>

                            <option value="expired">
                                Expired
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                        </select>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        wire:click="$set('modalOpen', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                        Cancel
                    </button>

                    <button
                        wire:click="save"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Save Subscription
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>