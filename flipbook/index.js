
        document.addEventListener('DOMContentLoaded', function () {
            const url = 'test2.pdf'; // Replace with the path to your PDF file
            const pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

            const flipbook = document.getElementById('flipbook');
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');
            const pageNumberInput = document.getElementById('pageNumber');
            const downloadButton = document.getElementById('download');
            let scale = 1.0;

            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                const totalPages = pdf.numPages;
                let loadedPages = 0;

                function renderPage(pageNumber, scale) {
                    return pdf.getPage(pageNumber).then(function(page) {
                        const viewport = page.getViewport({ scale });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');

                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };

                        return page.render(renderContext).promise.then(function() {
                            const pageDiv = document.createElement('div');
                            pageDiv.className = 'page';
                            pageDiv.appendChild(canvas);
                            return pageDiv;
                        });
                    });
                }

                for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
                    renderPage(pageNumber, scale).then(function(pageDiv) {
                        flipbook.appendChild(pageDiv);
                        loadedPages++;
                        if (loadedPages === totalPages) {
                            $('#flipbook').turn({
                                width: flipbook.clientWidth,
                                height: flipbook.clientHeight,
                                autoCenter: true,
                                display: 'double',
                                elevation: 50,
                                gradients: true,
                                duration: 1000,
                            });

                            // Enable buttons
                            nextButton.disabled = false;

                            prevButton.addEventListener('click', function() {
                                $('#flipbook').turn('previous');
                                updateButtons();
                            });

                            nextButton.addEventListener('click', function() {
                                $('#flipbook').turn('next');
                                updateButtons();
                            });

                            document.addEventListener('keydown', function(event) {
                                if (event.key === 'ArrowLeft') {
                                    $('#flipbook').turn('previous');
                                    updateButtons();
                                } else if (event.key === 'ArrowRight') {
                                    $('#flipbook').turn('next');
                                    updateButtons();
                                }
                            });

                            pageNumberInput.addEventListener('change', function() {
                                const pageNumber = parseInt(pageNumberInput.value);
                                if (pageNumber > 0 && pageNumber <= totalPages) {
                                    $('#flipbook').turn('page', pageNumber);
                                    updateButtons();
                                }
                            });

                            function updateButtons() {
                                const currentPage = $('#flipbook').turn('page');
                                const totalPages = $('#flipbook').turn('pages');
                                prevButton.disabled = (currentPage === 1);
                                nextButton.disabled = (currentPage === totalPages);
                            }

                            // Initial update of buttons
                            updateButtons();
                        }
                    }).catch(function(error) {
                        console.error('Error rendering page:', error);
                    });
                }
            }).catch(function(error) {
                console.error('Error getting document:', error);
            });

            // Download PDF on button click
            downloadButton.addEventListener('click', function() {
                const link = document.createElement('a');
                link.href = url;
                link.download = 'downloaded.pdf';
                link.click();
            });
        });