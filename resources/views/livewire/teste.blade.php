<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Mapa</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>Teste Leaflet</h1>

    <div id="map" style="height: 500px; width: 100%;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = L.map('map').setView([-22.8886768, -48.4500518], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        });
    </script>
</body>
</html>
