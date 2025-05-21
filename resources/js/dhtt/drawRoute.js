import '../modules/leaflet'
import '../modules/chart'

function parseGPX(xml) {
    const points = [];
    const waypoints = [];

    const trkpts = xml.querySelectorAll('trkpt');
    trkpts.forEach(pt => {
        points.push({
            lat: parseFloat(pt.getAttribute('lat')),
            lon: parseFloat(pt.getAttribute('lon')),
            ele: parseFloat(pt.querySelector('ele')?.textContent || 0)
        });
    });

    const wpts = xml.querySelectorAll('wpt');
    wpts.forEach(wpt => {
        waypoints.push({
            lat: parseFloat(wpt.getAttribute('lat')),
            lon: parseFloat(wpt.getAttribute('lon')),
            name: wpt.querySelector('name')?.textContent || "Waypoint"
        });
    });

    return {points, waypoints};
}

function getLatLngForDistance(distance, points, distances, closestPointIndex) {
    if (closestPointIndex === undefined || closestPointIndex >= points.length - 1) {
        console.error("Nem található megfelelő pont az interpolációhoz.");
        return [points[points.length - 1].lat, points[points.length - 1].lon];
    }

    const point1 = points[closestPointIndex];
    const point2 = points[closestPointIndex + 1];

    const ratio = (distance - distances[closestPointIndex]) / (distances[closestPointIndex + 1] - distances[closestPointIndex]);
    const lat = point1.lat + ratio * (point2.lat - point1.lat);
    const lon = point1.lon + ratio * (point2.lon - point1.lon);

    return [lat, lon];
}

function renderMap(mapElement, points, waypoints) {
    let map = L.map(mapElement).setView([points[0].lat, points[0].lon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(map);

    let routeCoords = points.map(point => [point.lat, point.lon]);
    L.polyline(routeCoords, {color: 'red'}).addTo(map);

    L.circleMarker([points[0].lat, points[0].lon], {
        color: '#ffffff',
        fillColor: '#51c152',
        fillOpacity: 0.8,
        radius: 8
    }).addTo(map).bindPopup('Start');

    L.circleMarker([points[points.length - 1].lat, points[points.length - 1].lon], {
        color: '#ffffff',
        fillColor: '#101010',
        fillOpacity: 0.8,
        radius: 8
    }).addTo(map).bindPopup('Finish');

    waypoints.forEach((wpt, index) => {
        let waypointMarker = L.marker([wpt.lat, wpt.lon], {
            icon: L.divIcon({
                className: 'waypoint-icon',
                html: (index + 1).toString(),
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            }),
            zIndexOffset: 1000
        }).addTo(map);

        let tooltipContent = `
            <div class="waypoint-tooltip-name">${wpt.name || `Waypoint ${index + 1}`}</div>
            <div class="waypoint-tooltip-coordinates">
                 ${wpt.lat.toFixed(4)} - ${wpt.lon.toFixed(4)}
            </div>
        `;

        waypointMarker.bindTooltip(tooltipContent, {
            permanent: false,
            direction: 'top',
            className: 'waypoint-tooltip',
        });

        waypointMarker.on('mouseout', function () {
            this.setIcon(L.divIcon({
                className: 'waypoint-icon',
                html: (index + 1).toString(),
                iconSize: [20, 20],
                iconAnchor: [10, 10],
            }));
        });
    });

    const followMarker = L.circleMarker([points[0].lat, points[0].lon], {
        color: '#ffffff',
        fillColor: '#51aec8',
        fillOpacity: 0,
        opacity: 0,
        radius: 10
    }).addTo(map);

    map.fitBounds(L.latLngBounds(routeCoords));
    return {map, followMarker};
}

function renderElevationChart(chartElement, map, followMarker, points) {
    let ctx = chartElement.getContext('2d');

    let totalDistance = 0;
    let distances = [0];

    function toRadians(degrees) {
        return degrees * Math.PI / 180;
    }

    function haversineDistance(lat1, lon1, lat2, lon2) {
        let R = 6371;
        let dLat = toRadians(lat2 - lat1);
        let dLon = toRadians(lon2 - lon1);
        let a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    for (let i = 1; i < points.length; i++) {
        let distance = haversineDistance(
            points[i - 1].lat, points[i - 1].lon,
            points[i].lat, points[i].lon
        );
        totalDistance += distance;
        distances.push(totalDistance);
    }

    const config = {
        type: 'line',
        data: {
            labels: distances,
            datasets: [{
                label: 'Magasság',
                data: points.map((point, index) => ({x: distances[index], y: point.ele})),
                fill: true,
                borderColor: '#3399ff',
                pointRadius: 0.5,
                tension: 0.3
            }],
        },
        options: {
            animation: false,
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    title: {
                        display: false,
                    },
                    ticks: {
                        callback: function (value) {
                            return value.toFixed(1) + ' km';
                        }
                    },
                    max: totalDistance,
                },
                y: {
                    title: {
                        display: false,
                    },
                    ticks: {
                        callback: function (value) {
                            return (value / 1000).toFixed(2) + ' km';
                        },
                        maxTicksLimit: 5
                    },
                }
            },
            plugins: {
                tooltip: {
                    intersect: false,
                    animation: false,
                    callbacks: {
                        title: ctx => 'Távolság: ' + parseFloat(ctx[0].label.replace(",", ".")).toFixed(2) + ' km',
                        label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toFixed(1) + ' m'
                    }
                }
            }
        },
        plugins: [{
            id: 'hoverLine',
            afterDatasetsDraw(chart) {
                const {ctx, tooltip, chartArea: {top, bottom}, scales: {x}} = chart;

                if (tooltip._active.length > 0) {
                    const xValue = tooltip.dataPoints[0].parsed.x;
                    const xCoor = x.getPixelForValue(xValue);

                    const idx = distances.findIndex(d => d >= xValue);
                    if (idx !== -1 && idx < points.length - 1) {
                        const latLng = getLatLngForDistance(xValue, points, distances, idx);
                        if (!isNaN(latLng[0])) {
                            followMarker.setLatLng(latLng);
                            followMarker.setStyle({fillOpacity: 1, opacity: 1});
                        }
                    }

                    ctx.save();
                    ctx.beginPath();
                    ctx.setLineDash([5, 5]);
                    ctx.moveTo(xCoor, top);
                    ctx.lineTo(xCoor, bottom);
                    ctx.strokeStyle = 'rgba(255, 0, 0, 1)';
                    ctx.lineWidth = 1;
                    ctx.stroke();
                    ctx.closePath();
                } else {
                    followMarker.setStyle({fillOpacity: 0, opacity: 0});
                }
            }
        }]
    };

    new Chart(ctx, config);
}

document.addEventListener("DOMContentLoaded", async function () {
    for (const element of document.querySelectorAll('.track-container')) {
        const gpxFileUrl = element.dataset.track;
        const mapElement = element.querySelector('.map-container');
        const chartElement = element.querySelector('.elevation-chart');

        try {
            const response = await fetch(gpxFileUrl);
            if (!response.ok) return console.error(`Failed to load GPX: ${response.statusText}`);

            const content = await response.text();
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(content, "application/xml");
            const result = parseGPX(xmlDoc);
            const {map, followMarker} = renderMap(mapElement, result.points, result.waypoints || []);
            renderElevationChart(chartElement, map, followMarker, result.points);
        } catch (error) {
            console.error('Error loading GPX file:', error);
        }
    }
});
