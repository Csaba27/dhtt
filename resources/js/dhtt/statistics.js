(function () {
    let statistics;

    const getChartColors = (canvasId) => {
        const defaultColors = {
            bg: 'rgba(54, 162, 235, 0.5)',
            border: 'rgba(54, 162, 235, 1)'
        };

        if (canvasId === 'notArrivedChart') {
            return {
                bg: '#ff8042',
                border: '#d5703f'
            };
        }

        return defaultColors;
    };

    const createStatsChart = (canvasId, label, data) => {
        const ctx = document.getElementById(canvasId)?.getContext('2d');
        if (!ctx) return;

        const participantData = data.map(item => item.count);
        const hikeNames = data.map(item => item.name);
        const {bg, border} = getChartColors(canvasId);

        const existingChart = Chart.getChart(canvasId);
        if (existingChart) existingChart.destroy();

        const config = {
            type: 'bar',
            data: {
                labels: hikeNames,
                datasets: [{
                    label,
                    data: participantData,
                    backgroundColor: bg,
                    borderColor: border,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {beginAtZero: true}
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        };

        if (canvasId === 'notArrivedChart') {
            config.options.onHover = (event, chartElement) => {
                event.chart.canvas.style.cursor = chartElement.length ? 'pointer' : 'default';
            };

            config.options.onClick = (event, _, chart) => {
                const points = chart.getElementsAtEventForMode(event, 'nearest', {intersect: true}, false);
                if (points.length) {
                    const index = points[0].index;
                    const hikeId = statistics['notArrived'][index].id;
                    Livewire.dispatchTo('admin.dhtt.not-arrived-list', 'filterHike', {hikeId});
                }
            };
        }

        new Chart(ctx, config);
    };

    const chartMap = [
        ['onlineChart', 'Online regisztrálók', 'onlineParticipants'],
        ['onsiteChart', 'Helyszínen regisztrálók', 'onSiteParticipants'],
        ['startChart', 'Rajtoltak', 'startParticipants'],
        ['arrivalChart', 'Beérkezők', 'arrivals'],
        ['notArrivedChart', 'Még be nem érkezettek', 'notArrived']
    ];

    const createCharts = () => {
        chartMap.forEach(([id, label, key]) => createStatsChart(id, label, statistics[key]));
    };

    const updateChart = (chartId, data) => {
        const chart = Chart.getChart(chartId);
        if (!chart) return;
        chart.data.datasets[0].data = data.map(item => item.count);
        chart.update();
    };

    const updateCharts = () => {
        chartMap.forEach(([id, , key]) => updateChart(id, statistics[key]));
    };

    const importAndCreateChart = () => {
        if (typeof Chart === 'undefined') {
            import('../modules/chart.js')
                .then(() => createCharts())
                .catch(error => console.error('Chart.js betöltés hiba:', error));
        } else {
            createCharts();
        }
    };

    const updateStatisticsLabels = () => {
        const labelMap = [
            ['total-online-participants', 'totalOnlineParticipants'],
            ['total-onsite-participants', 'totalOnSiteParticipants'],
            ['total-start-participants', 'totalStartParticipants'],
            ['total-arrivals', 'totalArrivals'],
            ['total-notarrivals', 'totalNotArrived']
        ];

        labelMap.forEach(([id, key]) => {
            const el = document.getElementById(id);
            if (el) el.innerText = statistics[key];
        });
    };

    const initializeStatistics = () => {
        statistics = Alpine.store('statistics');
        importAndCreateChart();

        Livewire.hook('component.init', ({component}) => {
            if (component.name === 'admin.dhtt.not-arrived-list') {
                setTimeout(() => {
                    statistics = Alpine.store('statistics');
                    importAndCreateChart();
                }, 100);
            }
        });

        Livewire.on('refreshStatisticsComplete', ([updatedStats]) => {
            statistics = updatedStats;
            updateStatisticsLabels();
            updateCharts();
        });
    };

    if (typeof Livewire !== 'object') {
        document.addEventListener('livewire:initialized', initializeStatistics);
    } else {
        initializeStatistics();
    }
})();
