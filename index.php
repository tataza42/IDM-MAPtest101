<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map พร้อมกราฟิกและเส้นทาง</title>
    <!-- Leaflet CSS -->
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
    <style>
        #map { height: 600px; width: 100%; }
        #search-box {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
        }
        #search-input {
            width: 200px;
        }
    </style>
    <style>
    /* สไตล์สำหรับแผนที่ */
    #map { 
        height: 600px; 
        width: 100%; 
    }

    /* สไตล์สำหรับกล่องค้นหา */
    #search-box {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* สไตล์สำหรับกล่อง input */
    #search-input {
        width: 250px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 14px;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.3s ease;
    }

    /* เมื่อผู้ใช้โฟกัสที่ช่อง input */
    #search-input:focus {
        border-color: #4CAF50;
    }

    /* สไตล์สำหรับปุ่มค้นหา */
    #search-btn {
        padding: 10px 15px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    /* เมื่อผู้ใช้โฟกัสหรือเอาเมาส์ไปบนปุ่ม */
    #search-btn:hover {
        background-color: #45a049;
    }
</style>

</head>
<body>
    <h1 style="text-align: center;">แผนที่พร้อมกราฟิกแสดงอาคาร ห้องน้ำ และเส้นทาง</h1>
    <div id="map"></div>
    <div id="search-box">
        <input type="text" id="search-input" placeholder="ค้นหาอาคารหรือห้อง...">
        <button id="search-btn">ค้นหา</button>
    </div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.js"></script>
    
    <script>
        var map = L.map('map').setView([7.2011801462897465, 100.6027025341781], 17);

        // เพิ่มแผนที่ดาวเทียมจาก Esri
        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            maxZoom: 19
        }).addTo(map);

        var buildings = [
            { id: 1, name: "อาคาร 63", coords: [7.203827, 100.600637], rooms: ["63202", "63204", "ห้องประชุม1"] },
            { id: 2, name: "อาคาร 51", coords: [7.203623, 100.599763], rooms: ["63201", "ห้องต้า"] },
            { id: 3, name: "อาคาร 4", coords: [7.203322, 100.599197], rooms: [] },
            { id: 4, name: "อาคาร 32", coords: [7.203970, 100.601774], rooms: [] }
            // เพิ่มข้อมูลอาคารได้ตามต้องการ
        ];

        // ฟังก์ชันค้นหา
        document.getElementById('search-btn').addEventListener('click', function() {
            var searchValue = document.getElementById('search-input').value.trim();

            // ค้นหาหมายเลขอาคารหรือห้องที่ตรงกับคำค้น
            var foundLocation = null;
            buildings.forEach(function(building) {
                // ค้นหาหมายเลขอาคาร
                if (building.name.includes(searchValue)) {
                    foundLocation = { type: "building", coords: building.coords, name: building.name };
                }

                // ค้นหาหมายเลขห้องในอาคาร
                building.rooms.forEach(function(room) {
                    if (room === searchValue) {
                        foundLocation = { type: "room", coords: building.coords, room: room, name: building.name };
                    }
                });
            });

            // ถ้ามีผลลัพธ์
            if (foundLocation) {
                // เลื่อนแผนที่ไปยังตำแหน่งที่ค้นพบ
                map.flyTo(foundLocation.coords, 19, {
                    duration: 1 // ระยะเวลาในการเลื่อน (วินาที)
                });

                // เพิ่ม Marker ชั่วคราวที่ตำแหน่งอาคาร
                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }

                currentMarker = L.marker(foundLocation.coords).addTo(map)
                    .bindPopup(foundLocation.type === "building" ? foundLocation.name : `ห้อง: ${foundLocation.room} ใน ${foundLocation.name}`)
                    .openPopup();
            } else {
                // หากไม่พบข้อมูล
                alert("ไม่พบอาคารหรือห้องที่ค้นหา");
            }
        });

        // ตัวแปรสำหรับจัดการ Marker
        var currentMarker = null;


    </script>
          <!-- เชื่อมโยงไฟล์ map.js -->
        <script src="building/63.js?v=1.0"></script>
        <script src="/building/51.js?v=1.1"></script>
        <script src="/building/2.js?v=1.2"></script>
        <script src="/building/4.js?v=1.3"></script>
        <script src="/building/5.js?v=1.4"></script>
        <script src="/building/6.js?v=1.5"></script>
        <script src="/building/37.js?v=1.6"></script>
        <script src="/building/33.js?v=1.7"></script>
        <script src="/building/32.js?v=1.8"></script>
        <script src="/building/34.js?v=1.9"></script>
        <script src="/building/35.js?v=1.10"></script>
        <script src="/building/35-2.js?v=1.11"></script>
        <script src="/building/62.js?v=1.12"></script>
        <script src="/building/58.js?v=1.13"></script>
        <script src="/building/55.js?v=1.14"></script>
        <script src="/building/60.js?v=1.15"></script>
</body>
</html>
