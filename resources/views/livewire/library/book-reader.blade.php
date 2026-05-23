<div
    class="min-h-screen bg-gray-100"
    x-data="{
        sidebarOpen: true,
        fullscreen: false,
        lastSavedPage: 0,
        saveTimer: null,

        toggleFullscreen() {
            const reader = document.getElementById('reader-wrapper');

            if (!document.fullscreenElement) {
                reader.requestFullscreen();
                this.fullscreen = true;
            } else {
                document.exitFullscreen();
                this.fullscreen = false;
            }
        },

        saveProgress(page) {
            if (page === this.lastSavedPage) return;

            clearTimeout(this.saveTimer);

            this.saveTimer = setTimeout(() => {
                this.lastSavedPage = page;
                $wire.saveProgress(page);
            }, 1200);
        }
    }"
    x-on:reader-page-viewed.window="saveProgress($event.detail.page)"
>

<div id="reader-wrapper" class="min-h-screen bg-gray-900 overflow-y-auto">
        {{-- Top Bar --}}
        <div class="sticky top-0 z-50 bg-white border-b px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-on:click="sidebarOpen = !sidebarOpen"
                    class="rounded bg-gray-200 px-3 py-2 text-sm">
                    ☰
                </button>

                <div>
                    <h1 class="font-semibold text-gray-800">
                        {{ $book->title }}
                    </h1>

                    <p class="text-xs text-gray-500">
                        {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">
                    Pages: <span id="page-count">0</span>
                </span>

                <button
                    type="button"
                    x-on:click="toggleFullscreen"
                    class="rounded bg-blue-600 px-3 py-2 text-sm text-white">
                    Full Screen
                </button>

                <a href="{{ route('library.index') }}"
                   class="rounded bg-gray-200 px-3 py-2 text-sm">
                    Back
                </a>
            </div>
        </div>

        @if (! $canRead)

            <div class="m-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Subscription Required</h2>
                <p>You need an active subscription to read this book.</p>
            </div>

        @else

            @if ($book->book_type === 'pdf')

                <div class="flex">

                    {{-- Collapsible Side Panel --}}
                    <aside
                        x-show="sidebarOpen"
                        x-transition
                        class="w-72 min-h-screen bg-white border-r p-4 hidden md:block"
                    >
                        <h2 class="font-semibold text-gray-800 mb-3">
                            Book Details
                        </h2>

                        @if ($book->cover_image)
                            <img
                                src="{{ asset('storage/' . $book->cover_image) }}"
                                class="w-full h-72 object-cover rounded mb-4">
                        @endif

                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Author:</strong>
                            {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown' }}
                        </p>

                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Category:</strong>
                            {{ $book->category?->name ?? 'General' }}
                        </p>

                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Last Page:</strong>
                            {{ $lastPage }}
                        </p>

                        <p class="text-sm text-gray-600">
                            {{ $book->description }}
                        </p>
                    </aside>

                    {{-- Reader Area --}}
                    <main class="flex-1 min-w-0">
                        <div wire:ignore>
                            <div id="pdf-loader" class="p-6 text-center text-gray-300">
                                Loading book, please wait...
                            </div>

                            <div
                                id="pdf-pages"
                                class="bg-gray-900 min-h-screen px-1 md:px-6 py-4">
                            </div>
                        </div>
                    </main>
                </div>

                <script>
                    function loadReader() {
                        if (window.BookReader) {
                            window.BookReader.openPdfLazy(
                                @js(asset('storage/' . $book->pdf_path)),
                                @js($lastPage)
                            );
                        }
                    }

                    document.addEventListener('DOMContentLoaded', loadReader);
                    document.addEventListener('livewire:navigated', loadReader);
                </script>

            @elseif ($book->book_type === 'epub')

                <div class="m-6 bg-yellow-100 text-yellow-700 p-4 rounded">
                    EPUB reader will be added next.
                </div>

            @else

                <div class="m-6 bg-yellow-100 text-yellow-700 p-4 rounded">
                    Reader for {{ strtoupper($book->book_type) }} will be added later.
                </div>

            @endif

        @endif

    </div>
</div>