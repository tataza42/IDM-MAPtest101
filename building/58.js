// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var building = L.polygon([
    [7.2022631768530525, 100.60241834359802],
    [7.202160238685974, 100.60247189550567],
    [7.202216688651454, 100.60259573429214],
    [7.202107109300266, 100.6026693681652],
    [7.202322947390939, 100.60305092550729],
    [7.202691532200551, 100.60283002388819],
    [7.202462411948737, 100.60246520151723],
    [7.20234287090182, 100.60253548839603],
   
], {
    color: 'blue',
    fillColor: '#3388ff',
    fillOpacity: 0.5
}).addTo(map); // เพิ่ม Polygon ลงในแผนที่

// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 58", {
    permanent: false, // แสดงเมื่อเมาส์ชี้เท่านั้น
    direction: "top"
});

// เมื่อคลิกที่ Polygon (อาคาร) จะเรียกใช้ SweetAlert2
building.on('click', function() {
    Swal.fire({
        title: 'รายละเอียดอาคาร 63',
        text: 'ห้อง 63202, ห้อง 63203, ห้อง 63204',
        icon: 'info',
        confirmButtonText: 'ตกลง',
        imageUrl: "https://lh5.googleusercontent.com/p/AF1QipP_ZGOFScmSQM8ei2KnjJKhqfZ12p_WrxYRm4ma=w408-h270-k-no",
        imageHeight: 400,
        imageAlt: "อาคาร 63"
    });
});
