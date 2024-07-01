<x-app-layout>
    @section('namefitur', 'Maps')
    @push('styles')
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 200px);
            background-color: lightblue;
            border: 2px solid white;
            margin-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />
    @endpush

    <x-slot name="pagesInit">Pemantauan Map</x-slot>
    <x-slot name="pages">Pemantauan Map</x-slot>
    <button class="btn btn-sm btn-primary" onclick="resetMap()">Refresh</button>

    <section class="section">
        <div id="map"></div>
    </section>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>
    <script>
        let map, markers = [];
        const defaultLocation = {
            lat: -6.3707762,
            lng: 106.8236706,
        };

        /* ----------------------------- Initialize Map ----------------------------- */
        function initMap() {
            map = L.map('map', {
                center: defaultLocation,
                zoom: 14
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            map.on('click', mapClicked);
            initMarkers();
        }
        initMap();

        /* --------------------------- Initialize Markers --------------------------- */
        function initMarkers() {
            const initialMarkers = @json($initialMarkers);
            console.log(initialMarkers); // Debugging line

            for (let index = 0; index < initialMarkers.length; index++) {
                const data = initialMarkers[index];
                const marker = generateMarker(data, index);
                marker.addTo(map).bindPopup(generatePopupContent(data));
                markers.push(marker);
            }
        }

        function generateMarker(data, index) {
            const customIcon = L.icon({
                iconUrl: data.icon_url,
                iconSize: [41, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            return L.marker(data.position, {
                icon: customIcon,
                draggable: data.draggable
            })
            .on('click', (event) => markerClicked(event, index))
            .on('dragend', (event) => markerDragEnd(event, index));
        }

        function generatePopupContent(data) {
    let deskripsi = data.deskripsi_laporan.length > 30 ? data.deskripsi_laporan.substring(0, 30) + '...' : data.deskripsi_laporan;
    let alamat = data.alamat.length > 30 ? data.alamat.substring(0, 30) + '...' : data.alamat;

    // Tentukan gaya berdasarkan status laporan
    let statusStyle = '';
    let statusText = '';
    switch (data.status_laporan) {
        case 'perlu-dukungan':
            statusStyle = 'font-weight: bold; background-color: #FFFFFF ; color: #ffc107; border: 1px solid #ffeeba; border-radius: 4px;'; // Warna kuning untuk perlu-dukungan
            statusText = 'Perlu Dukungan';
            break;
        case 'perlu-diatasi':
            statusStyle = 'font-weight: bold; background-color: #FFFFFF; color: #e51c23; border: 1px solid #f5c6cb; border-radius: 4px;'; // Warna merah untuk perlu-diatasi
            statusText = 'Perlu Diatasi';
            break;
        case 'sedang-diatasi':
            statusStyle = 'font-weight: bold; background-color: #FFFFFF; color: #17a2b8; border: 1px solid #bee5eb; border-radius: 4px;'; // Warna biru untuk sedang-diatasi
            statusText = 'Sedang Diatasi';
            break;
        case 'sudah-teratasi':
            statusStyle = 'font-weight: bold; background-color: #FFFFFF; color: #28a745; border: 1px solid #c3e6cb; border-radius: 4px;'; // Warna hijau untuk sudah-teratasi
            statusText = 'Sudah Teratasi';
            break;
        default:
            statusStyle = 'font-weight: bold; background-color: #FFFFFF; color: #212529; border: 1px solid #ccc; border-radius: 4px;'; // Default background
            statusText = 'Status Tidak Diketahui';
    }

    return `
        <div style="max-width: 200px;">
            <div style="margin-bottom: 8px; position: relative;">
                <img src="${data.image_laporan}" style="max-width: 100%; height: auto;" />
                <span style="font-size: 10px; ${statusStyle}; position: absolute; bottom: 4px; left: 4px; padding: 2px 6px; border-radius: 4px;">${statusText}</span>
            </div>
            <h4 style="font-size: 14px; margin-bottom: 4px;">${data.judul_laporan}</h4>
            <span style="color: #4A4A4A; font-size: 13px; max-height: 50px; overflow: hidden;">${deskripsi}</span><br>
            <span style="font-size: 9px;"><i style="color: #FE8235;" class="bi bi-geo-alt"></i> ${alamat}</span> <br>
            <span style="font-size: 9px;"><i style="color: #FE8235;" class="bi bi-person"></i> ${data.jumlahPendukung} Orang mendukung</span>
        </div>
    `;
}




        /* ------------------------- Handle Map Click Event ------------------------- */
        function mapClicked(event) {
            console.log(map);
            console.log(event.latlng.lat, event.latlng.lng);
        }

        /* ------------------------ Handle Marker Click Event ----------------------- */
        function markerClicked(event, index) {
            console.log(map);
            console.log(event.latlng.lat, event.latlng.lng);
        }

        /* ----------------------- Handle Marker DragEnd Event ---------------------- */
        function markerDragEnd(event, index) {
            console.log(map);
            console.log(event.target.getLatLng());
        }

        /* ----------------------- Reset Map to Default Location --------------------- */
        function resetMap() {
            map.setView(defaultLocation, 13);
        }
    </script>
    @endpush
</x-app-layout>