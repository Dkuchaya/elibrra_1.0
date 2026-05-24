<aside
    x-data="{ openGroup: 'library', collapsed: false }"
    class="min-h-screen border-r bg-white shadow-sm transition-all duration-300"
    :class="collapsed ? 'w-20' : 'w-72'"
>
    {{-- Header --}}
    <div class="flex items-center justify-between border-b px-4 py-4">
        <div x-show="!collapsed" x-transition>
            <h2 class="text-lg font-bold text-gray-800">eLibrra</h2>
            <p class="text-xs text-gray-500">Digital Reading Portal</p>
        </div>

        <button
            type="button"
            @click="collapsed = !collapsed"
            class="rounded-lg bg-gray-100 px-3 py-2 text-gray-700 hover:bg-blue-100 hover:text-blue-700">
            ☰
        </button>
    </div>

    <nav class="space-y-2 px-3 py-4">

        {{-- Dashboard --}}
        @can('view dashboard')
            <a href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard')
                    ? 'bg-blue-100 text-blue-700'
                    : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}
                    flex items-center gap-3 rounded-xl px-4 py-3 transition">

                <span class="text-lg">🏠</span>
                <span x-show="!collapsed" x-transition>Dashboard</span>
            </a>
        @endcan

        {{-- Library --}}
        <div>
            <button
                type="button"
                @click="collapsed ? collapsed = false : openGroup = openGroup === 'library' ? null : 'library'"
                class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-gray-700 transition hover:bg-blue-50 hover:text-blue-700">

                <div class="flex items-center gap-3">
                    <span class="text-lg">📚</span>
                    <span x-show="!collapsed" x-transition>Library</span>
                </div>

                <span
                    x-show="!collapsed"
                    class="transition-transform duration-200"
                    :class="openGroup === 'library' ? 'rotate-90' : ''">
                    ▶
                </span>
            </button>

            <div
                x-show="!collapsed && openGroup === 'library'"
                x-transition
                class="ml-5 mt-1 space-y-1 border-l pl-3">

                <a href="{{ route('library.index') }}"
                   class="{{ request()->routeIs('library.index')
                        ? 'bg-blue-100 text-blue-700'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                        block rounded-lg px-4 py-2 text-sm transition">
                    Browse Books
                </a>

                <a href="{{ route('my-library.index') }}"
                   class="{{ request()->routeIs('my-library.*')
                        ? 'bg-blue-100 text-blue-700'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                        block rounded-lg px-4 py-2 text-sm transition">
                    My Library
                </a>
            </div>
        </div>

        {{-- Management --}}
        @canany(['manage users', 'manage books', 'manage categories', 'manage authors', 'manage publishers'])
            <div>
                <button
                    type="button"
                    @click="collapsed ? collapsed = false : openGroup = openGroup === 'management' ? null : 'management'"
                    class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-gray-700 transition hover:bg-blue-50 hover:text-blue-700">

                    <div class="flex items-center gap-3">
                        <span class="text-lg">🗂️</span>
                        <span x-show="!collapsed" x-transition>Management</span>
                    </div>

                    <span
                        x-show="!collapsed"
                        class="transition-transform duration-200"
                        :class="openGroup === 'management' ? 'rotate-90' : ''">
                        ▶
                    </span>
                </button>

                <div
                    x-show="!collapsed && openGroup === 'management'"
                    x-transition
                    class="ml-5 mt-1 space-y-1 border-l pl-3">

                    @can('manage users')
                        <a href="{{ route('users.index') }}"
                           class="{{ request()->routeIs('users.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            Users
                        </a>
                    @endcan

                    @can('manage books')
                        <a href="{{ route('admin.books.index') }}"
                           class="{{ request()->routeIs('admin.books.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            Books
                        </a>
                    @endcan

                    @can('manage categories')
                        <a href="{{ route('book-categories.index') }}"
                           class="{{ request()->routeIs('book-categories.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            Categories
                        </a>
                    @endcan

                    @can('manage authors')
                        <a href="{{ route('authors.index') }}"
                           class="{{ request()->routeIs('authors.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            Authors
                        </a>
                    @endcan

                    @can('manage publishers')
                        <a href="{{ route('publishers.index') }}"
                           class="{{ request()->routeIs('publishers.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            Publishers
                        </a>
                    @endcan
                </div>
            </div>
        @endcanany

        {{-- Subscriptions --}}
        @canany(['manage subscription plans', 'manage school subscriptions', 'manage user subscriptions', 'manage subscriptions'])
            <div>
                <button
                    type="button"
                    @click="collapsed ? collapsed = false : openGroup = openGroup === 'subscriptions' ? null : 'subscriptions'"
                    class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-gray-700 transition hover:bg-blue-50 hover:text-blue-700">

                    <div class="flex items-center gap-3">
                        <span class="text-lg">💳</span>
                        <span x-show="!collapsed" x-transition>Subscriptions</span>
                    </div>

                    <span
                        x-show="!collapsed"
                        class="transition-transform duration-200"
                        :class="openGroup === 'subscriptions' ? 'rotate-90' : ''">
                        ▶
                    </span>
                </button>

                <div
                    x-show="!collapsed && openGroup === 'subscriptions'"
                    x-transition
                    class="ml-5 mt-1 space-y-1 border-l pl-3">

                    @canany(['manage subscription plans', 'manage subscriptions'])
                        <a href="{{ route('admin.subscription-plans.index') }}"
                           class="{{ request()->routeIs('admin.subscription-plans.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            Plans
                        </a>
                    @endcanany

                    @canany(['manage school subscriptions', 'manage subscriptions'])
                        <a href="{{ route('admin.school-subscriptions.index') }}"
                           class="{{ request()->routeIs('admin.school-subscriptions.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            School Subscriptions
                        </a>
                    @endcanany

                    @canany(['manage user subscriptions', 'manage subscriptions'])
                        <a href="{{ route('admin.user-subscriptions.index') }}"
                           class="{{ request()->routeIs('admin.user-subscriptions.*')
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                                block rounded-lg px-4 py-2 text-sm transition">
                            User Subscriptions
                        </a>
                    @endcanany
                </div>
            </div>
        @endcanany

        {{-- System --}}
        @role('Super Admin')
            <div>
                <button
                    type="button"
                    @click="collapsed ? collapsed = false : openGroup = openGroup === 'system' ? null : 'system'"
                    class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-gray-700 transition hover:bg-blue-50 hover:text-blue-700">

                    <div class="flex items-center gap-3">
                        <span class="text-lg">⚙️</span>
                        <span x-show="!collapsed" x-transition>System</span>
                    </div>

                    <span
                        x-show="!collapsed"
                        class="transition-transform duration-200"
                        :class="openGroup === 'system' ? 'rotate-90' : ''">
                        ▶
                    </span>
                </button>

                <div
                    x-show="!collapsed && openGroup === 'system'"
                    x-transition
                    class="ml-5 mt-1 space-y-1 border-l pl-3">

                    <a href="{{ route('schools.index') }}"
                       class="{{ request()->routeIs('schools.*')
                            ? 'bg-blue-100 text-blue-700'
                            : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                            block rounded-lg px-4 py-2 text-sm transition">
                        Schools
                    </a>

                    <a href="{{ route('school-admins.index') }}"
                       class="{{ request()->routeIs('school-admins.*')
                            ? 'bg-blue-100 text-blue-700'
                            : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                            block rounded-lg px-4 py-2 text-sm transition">
                        School Admins
                    </a>

                    <a href="{{ route('admin.access-control.index') }}"
                       class="{{ request()->routeIs('admin.access-control.*')
                            ? 'bg-blue-100 text-blue-700'
                            : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}
                            block rounded-lg px-4 py-2 text-sm transition">
                        Access Control
                    </a>
                </div>
            </div>
        @endrole

    </nav>
</aside>