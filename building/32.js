// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var building = L.polygon([
    [7.204345179308888, 100.60127032453453],
    [7.203574806319701, 100.60171882176121],
    [7.203926787244445, 100.60233801569358],
    [7.204617466000517, 100.601949764363],
    
   
], {
    color: 'blue',
    fillColor: '#3388ff',
    fillOpacity: 0.5
}).addTo(map); // เพิ่ม Polygon ลงในแผนที่

// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 32", {
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
