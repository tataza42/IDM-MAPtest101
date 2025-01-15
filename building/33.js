// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var building = L.polygon([
    [7.203122279262359, 100.60036078573673],
    [7.2028545244562645, 100.60055476535398],
    [7.2030260548971405, 100.60081621614708],
    [7.203297993267946, 100.60066229753775],
    
   
], {
    color: 'blue',
    fillColor: '#3388ff',
    fillOpacity: 0.5
}).addTo(map); // เพิ่ม Polygon ลงในแผนที่

// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 33", {
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
