// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var footballFieldCoords = [
    [7.200993, 100.601106],  // มุมซ้ายบน
    [7.200508, 100.601424],  // จุดโค้ง 1
    [7.201083, 100.602373],  // จุดโค้ง 2
    [7.201580, 100.602084],  // มุมขวาบน
    
    
];

// วาดสนามฟุตบอล
var footballField = L.polygon(footballFieldCoords, {
    color: 'green',        // สีขอบ
    fillColor: '#32CD32',  // สีภายใน
    fillOpacity: 0.5       // ความโปร่งใส
}).addTo(map);
// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 35", {
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
