// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var building = L.polygon([
    [7.2028124282470545, 100.5994253771939],
    [7.202892231067636, 100.59957476129868],
    [7.202325467874192, 100.59988502059322],
    [7.202247293584995, 100.59976682657624],
    
   
], {
    color: 'blue',
    fillColor: '#3388ff',
    fillOpacity: 0.5
}).addTo(map); // เพิ่ม Polygon ลงในแผนที่

// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 6", {
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
