// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var building = L.polygon([
    [7.2039582487831, 100.60019232568929],
    [7.203731899172621, 100.60026966486215],
    [7.20378177282531, 100.60044947843906],
    [7.203678189078961, 100.60051715021534],
    [7.203578441745278, 100.60069503031292],
    [7.203586114617882, 100.60092124739356],
    [7.20370888056182, 100.60088451128644],
    [7.203785609260848, 100.60101018745236],
    [7.2038796018983, 100.60105465747677],
    [7.204077178195116, 100.60101212093168],
    [7.204151988615118, 100.6008787108585],
    [7.204175007203402, 100.60074336730598],
   
   
], {
    color: 'blue',
    fillColor: '#3388ff',
    fillOpacity: 0.5
}).addTo(map); // เพิ่ม Polygon ลงในแผนที่

// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 63", {
    permanent: false, // แสดงเมื่อเมาส์ชี้เท่านั้น
    direction: "top"
});

     // เมื่อคลิกที่ Polygon จะขอเส้นทาง
     building.on('click', function() {
        Swal.fire({
            title: 'ต้องการขอเส้นทางหรือไม่?',
            text: 'คุณต้องการขอเส้นทางจากตำแหน่งปัจจุบันไปยังอาคาร 63',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ขอเส้นทาง'
        }).then((result) => {
            if (result.isConfirmed) {
                // รับตำแหน่งปัจจุบันจาก GPS ของผู้ใช้
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userCoords = [position.coords.latitude, position.coords.longitude];

                    // ใช้ Leaflet Routing Machine เพื่อแสดงเส้นทาง
                    L.Routing.control({
                        waypoints: [
                            L.latLng(userCoords), 
                            L.latLng(buildingCoords)
                        ],
                        routeWhileDragging: true
                    }).addTo(map);

                    // เลื่อนแผนที่ไปยังเส้นทาง
                    map.setView(userCoords, 17);
                }, function(error) {
                    Swal.fire({
                        title: 'ไม่สามารถรับตำแหน่งได้',
                        text: 'กรุณาตรวจสอบการตั้งค่า GPS ของคุณ',
                        icon: 'error'
                    });
                });
            }
        });
    });