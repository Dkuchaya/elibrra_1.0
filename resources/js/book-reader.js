import * as pdfjsLib from 'pdfjs-dist';
import pdfWorker from 'pdfjs-dist/build/pdf.worker.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfWorker;

window.BookReader = {
    openPdf(url, startPage = 1) {
        return this.openPdfLazy(url, startPage);
    },

    async openPdfLazy(url, startPage = 1) {
        const container = document.getElementById('pdf-pages');
        const loader = document.getElementById('pdf-loader');
        const pageCountEl = document.getElementById('page-count');

        if (!container) return;

        container.innerHTML = '';

        if (loader) {
            loader.classList.remove('hidden');
            loader.innerHTML = 'Loading book, please wait...';
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
            const scale = isMobile ? 1.25 : 1.7;

            for (let i = 1; i <= pdf.numPages; i++) {
                const wrapper = document.createElement('div');

                wrapper.className = 'pdf-page-wrapper my-4 flex justify-center';
                wrapper.dataset.page = i;

                wrapper.innerHTML = `
                    <div class="text-center w-full">
                        <div class="text-xs text-gray-300 mb-2">Page ${i}</div>
                        <canvas class="pdf-page bg-white shadow rounded"></canvas>
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

                canvas.style.width = '100%';
                canvas.style.maxWidth = isMobile ? '100%' : '900px';
                canvas.style.height = 'auto';

                await page.render({
                    canvasContext: canvas.getContext('2d'),
                    viewport,
                }).promise;

                window.dispatchEvent(new CustomEvent('reader-page-viewed', {
                    detail: {
                        page: pageNumber,
                    },
                }));
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

            setTimeout(() => {
                const target = document.querySelector(`[data-page="${startPage}"]`);

                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                }
            }, 500);

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