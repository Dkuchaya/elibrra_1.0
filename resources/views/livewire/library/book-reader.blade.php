<div class="min-h-screen bg-gray-100">

    <div class="p-6 bg-white border-b">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $book->title }}
        </h1>

        <p class="text-sm text-gray-500">
            {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}
        </p>
    </div>

    @if (! $canRead)

        <div class="m-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
            <h2 class="font-semibold mb-2">Subscription Required</h2>
            <p>You need an active subscription to read this book.</p>
        </div>

    @else

        @if ($book->book_type === 'pdf')

            <div>
                <div class="sticky top-0 z-40 bg-white border-b px-4 py-3 flex justify-between items-center">
                    <div>
                        <h2 class="font-semibold text-gray-800">
                            PDF Reader
                        </h2>

                        <p class="text-xs text-gray-500">
                            Pages: <span id="page-count">0</span>
                        </p>
                    </div>

                    
                </div>

                <div id="pdf-loader" class="p-6 text-center text-gray-600">
                    Loading book, please wait...
                </div>

                <div id="pdf-pages" class="bg-gray-900 min-h-screen px-2 md:px-6 py-4"></div>

                <script>
                    function loadReader() {
                        if (window.BookReader) {
                            window.BookReader.openPdfLazy(@js(asset('storage/' . $book->pdf_path)));
                        }
                    }

                    document.addEventListener('DOMContentLoaded', loadReader);
                    document.addEventListener('livewire:navigated', loadReader);
                </script>
            </div>

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