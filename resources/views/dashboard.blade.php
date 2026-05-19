<x-app-layout>

    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-bold">Schools</h2>
                    <p class="text-3xl font-bold mt-2">
                        {{ \App\Models\School::count() }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-bold">Users</h2>
                    <p class="text-3xl font-bold mt-2">
                        {{ \App\Models\User::count() }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-bold">Books</h2>
                    <p class="text-3xl font-bold mt-2">
                        {{ \App\Models\Book::count() }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-bold">Subscriptions</h2>
                    <p class="text-3xl font-bold mt-2">
                        0
                    </p>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>