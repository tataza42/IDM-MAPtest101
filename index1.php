<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแสดงตำแหน่ง GPS และการเคลื่อนที่บนแผนที่</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">การแสดงตำแหน่ง GPS และการเคลื่อนที่</h1>
    <div id="map"></div>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    <script>
        var map = L.map('map').setView([7.2011801462897465, 100.6027025341781], 17); // ตั้งแผนที่เริ่มต้น

        // เพิ่มแผนที่เบส
        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            maxZoom: 19
        }).addTo(map);

        // สร้าง Marker สำหรับตำแหน่งปัจจุบันของผู้ใช้
        var userMarker = L.marker([7.2011801462897465, 100.6027025341781], {
            icon: L.icon({
                iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/4/4f/Iconic_red_location_pin.jpg',
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        }).addTo(map);

        // ฟังก์ชันที่จะอัพเดตตำแหน่งของผู้ใช้
        function updatePosition(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            // อัพเดตตำแหน่ง Marker
            userMarker.setLatLng([lat, lon]);

            // เลื่อนแผนที่ไปยังตำแหน่งของผู้ใช้
            map.setView([lat, lon], 17);
        }

        // เริ่มจับตำแหน่งของผู้ใช้
        function startTracking() {
            if (navigator.geolocation) {
                // ติดตามตำแหน่งผู้ใช้ในระยะเวลาต่อเนื่อง
                navigator.geolocation.watchPosition(updatePosition, function(error) {
                    alert("ไม่สามารถรับตำแหน่งได้: " + error.message);
                }, {
                    enableHighAccuracy: true, // ใช้ความแม่นยำสูง
                    maximumAge: 0, // ไม่เก็บตำแหน่งเก่า
                    timeout: 5000 // 5 วินาที
                });
            } else {
                alert("เบราว์เซอร์ไม่รองรับ GPS");
            }
        }

        // เริ่มติดตามตำแหน่งเมื่อเริ่มโหลด
        startTracking();
    </script>
</body>
</html>
