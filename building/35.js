// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var footballFieldCoords = [
    [7.202367, 100.600450],  // มุมซ้ายบน
    [7.201238, 100.601129],  // จุดโค้ง 1
    [7.201249, 100.601390],  // จุดโค้ง 2
    [7.201448, 100.601701],  // มุมขวาบน
    [7.201727, 100.601739],  // จุดโค้ง 3
    [7.202672, 100.601250],  // จุดโค้ง 4
    [7.202769, 100.601000],  // มุมขวาล่าง
    [7.202717, 100.600726],  // จุดโค้ง 5
    [7.202544, 100.600548],  // จุดโค้ง 6
    [7.202401, 100.600518],  // มุมซ้ายล่าง
    
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
