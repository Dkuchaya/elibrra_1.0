import * as pdfjsLib from 'pdfjs-dist';
import pdfWorker from 'pdfjs-dist/build/pdf.worker.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfWorker;

window.BookReader = {
    openPdf(url) {
        return this.openPdfLazy(url);
    },

    async openPdfLazy(url) {
        const container = document.getElementById('pdf-pages');
        const loader = document.getElementById('pdf-loader');
        const pageCountEl = document.getElementById('page-count');

        if (!container) return;

        container.innerHTML = '';

        if (loader) {
            loader.classList.remove('hidden');
        }

        const isMobile = window.innerWidth <= 768;

        try {
            const pdf = await pdfjsLib.getDocument({
                url,
                disableAutoFetch: true,
                rangeChunkSize: isMobile ? 512 * 1024 : 1024 * 1024,
            }).promise;

            if (pageCountEl) {
                pageCountEl.textContent = pdf.numPages;
            }

            const renderedPages = new Set();
            const scale = isMobile ? 1.1 : 1.5;

            for (let i = 1; i <= pdf.numPages; i++) {
                const wrapper = document.createElement('div');

                wrapper.className = 'pdf-page-wrapper my-4 flex justify-center';
                wrapper.dataset.page = i;

                wrapper.innerHTML = `
                    <div class="text-center w-full">
                        <div class="text-xs text-gray-300 mb-2">Page ${i}</div>
                        <canvas class="pdf-page bg-white shadow rounded max-w-full"></canvas>
                    </div>
                `;

                container.appendChild(wrapper);
            }

            async function renderPage(pageNumber, wrapper) {
                if (renderedPages.has(pageNumber)) return;

                renderedPages.add(pageNumber);

                const canvas = wrapper.querySelector('canvas');
                const page = await pdf.getPage(pageNumber);
                const viewport = page.getViewport({ scale });

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                await page.render({
                    canvasContext: canvas.getContext('2d'),
                    viewport,
                }).promise;
            }

            const observer = new IntersectionObserver(
                entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const wrapper = entry.target;
                            const pageNumber = Number(wrapper.dataset.page);

                            renderPage(pageNumber, wrapper);
                        }
                    });
                },
                {
                    root: null,
                    rootMargin: isMobile ? '600px' : '900px',
                    threshold: 0.01,
                }
            );

            document.querySelectorAll('.pdf-page-wrapper').forEach(wrapper => {
                observer.observe(wrapper);
            });

            if (loader) {
                loader.classList.add('hidden');
            }

        } catch (error) {
            console.error('PDF loading error:', error);

            if (loader) {
                loader.innerHTML = 'Failed to load PDF file.';
            }
        }
    }
};