(function ($) {
    "use strict";

    // Ensure the localized data is available
    if (typeof slpiQuadrantData === 'undefined' || !slpiQuadrantData.chartPoints) {
        console.error('Error: Localized data "slpiQuadrantData.chartPoints" is not defined or malformed.');
        return;
    }

    const dataPoints = slpiQuadrantData.chartPoints;

    // Validate the structure of the data points
    if (!Array.isArray(dataPoints) || dataPoints.some(point => !('x' in point && 'y' in point && 'url' in point && 'post_type' in point))) {
        console.error('Error: Data points are missing required properties (x, y, url, post_type).');
        return;
    }

    console.log('Received data points:', dataPoints);

    const datasetMap = {
        'home': {
            data: dataPoints.filter(point => point.post_type === 'home').map(point => ({
                x: parseFloat(point.x),
                y: parseFloat(point.y),
                url: point.url,
                post_type: point.post_type
            })),
            backgroundColor: 'rgba(153, 102, 255, 0.8)', // Violet pastel
            label: 'Home',
            pointStyle: 'triangle',
            pointRadius: 10,
            pointHoverRadius: 12
        },
        'page': {
            data: dataPoints.filter(point => point.post_type === 'page').map(point => ({
                x: parseFloat(point.x),
                y: parseFloat(point.y),
                url: point.url,
                post_type: point.post_type
            })),
            backgroundColor: 'rgba(102, 178, 255, 0.8)', // Bleu ciel pastel
            label: 'Pages',
            pointStyle: 'rect',
            pointRadius: 10,
            pointHoverRadius: 12
        },
        'post': {
            data: dataPoints.filter(point => point.post_type === 'post').map(point => ({
                x: parseFloat(point.x),
                y: parseFloat(point.y),
                url: point.url,
                post_type: point.post_type
            })),
            backgroundColor: 'rgba(255, 159, 128, 0.8)', // Rose corail pastel
            label: 'Posts',
            pointStyle: 'circle',
            pointRadius: 10,
            pointHoverRadius: 12
        }
    };
    

// Add a dummy dataset to avoid first dataset rendering issue
const dummyDataset = {
    data: [{ x: 0, y: 0 }], // Single invisible point
    backgroundColor: 'rgba(0, 0, 0, 0)', // Fully transparent
    pointStyle: 'circle',
    pointRadius: 0, // Invisible
    pointHoverRadius: 0,
    label: '', // Explicitly set an empty label
    borderWidth: 0 // Remove any border around the dummy point
};

    // Generate datasets with dummy dataset at the beginning
    let datasetIdCounter = 1;
    const datasets = [dummyDataset].concat(
        Object.keys(datasetMap).map(key => ({
            id: datasetIdCounter++,
            label: datasetMap[key].label,
            data: datasetMap[key].data,
            backgroundColor: datasetMap[key].backgroundColor,
            pointStyle: datasetMap[key].pointStyle,
            pointRadius: datasetMap[key].pointRadius,
            pointHoverRadius: datasetMap[key].pointHoverRadius,
            type: 'scatter',
            showLine: false
        }))
    );

    console.log('Generated datasets:', datasets);

    // Get the canvas element
    const ctx = document.getElementById('slpiQuadrantChart');
    if (!ctx) {
        console.error('Error: Canvas element with ID "slpiQuadrantChart" not found.');
        return;
    }

    // Destroy any existing Chart instance to avoid conflicts
    if (window.quadrantChartInstance) {
        window.quadrantChartInstance.destroy();
    }

    // Initialize the Chart.js instance
    window.quadrantChartInstance = new Chart(ctx, {
        type: 'scatter',
        data: { datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: { display: true, text: 'Mobile Score (0-100)' },
                    min: 0,
                    max: 100,
                    grid: { display: true, color: 'rgba(0, 0, 0, 0.1)' }
                },
                y: {
                    title: { display: true, text: 'Desktop Score (0-100)' },
                    min: 0,
                    max: 100,
                    grid: { display: true, color: 'rgba(0, 0, 0, 0.1)' }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const { x, y, url, post_type } = context.raw;
                            return `Type: ${post_type}, URL: ${url}, Scores: (${x}, ${y})`;
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: { usePointStyle: true }
                }
            },
            onClick: (event, elements) => {
                if (elements.length) {
                    const { datasetIndex, index } = elements[0];
                    const pointData = datasets[datasetIndex].data[index];
                    console.log('Clicked data point:', pointData);
                    openModal(pointData);
                }
            }
        }
    });

    console.log('Chart instance created successfully.');

    // Modal management functions
    function openModal(data) {
        $('#modalPageName').text(data.url);
        $('#modalScoreMobile').text(data.x);
        $('#modalScoreDesktop').text(data.y);
        $('#modalPostType').text(data.post_type);
        $('#modalURL').attr('href', data.url).text(data.url);
        $('#detailModal').show();
    }

    function closeModal() {
        $('#detailModal').hide();
    }

    // Event listeners for modal interactions
    $(document).on('click', '.custom-close', closeModal);
    $(window).on('click', function (event) {
        if ($(event.target).is('#detailModal')) {
            closeModal();
        }
    });

})(jQuery);