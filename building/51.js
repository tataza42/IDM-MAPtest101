// 1. วาด Polygon สำหรับพื้นที่อาคาร โดยเพิ่มพิกัดทั้งหมด 12 จุด
var building = L.polygon([
    [7.203863615614557, 100.59981373134362],
    [7.203694238545633, 100.59993192536056],
    [7.203611178613693, 100.59980059867506],
    [7.203472745359998, 100.59987939468637],
    [7.203394571268645, 100.5997447848337],
    [7.203653522894628, 100.59960525023034],
    [7.20373006831549, 100.59974314325014],
    [7.203791956093121, 100.59971031157875],
   
], {
    color: 'blue',
    fillColor: '#3388ff',
    fillOpacity: 0.5
}).addTo(map); // เพิ่ม Polygon ลงในแผนที่

// เพิ่ม Tooltip ที่แสดงเมื่อชี้เมาส์
building.bindTooltip("อาคาร 51", {
    permanent: false, // แสดงเมื่อเมาส์ชี้เท่านั้น
    direction: "top"
});

// เมื่อคลิกที่ Polygon (อาคาร) จะเรียกใช้ SweetAlert2
building.on('click', function() {
    Swal.fire({
        title: 'รายละเอียดอาคาร 51',
        text: 'ห้อง 51202, ห้อง 51203, ห้อง 51204 ห้อง 51204 ห้อง 51204',
        icon: 'info',
        confirmButtonText: 'ตกลง',
        imageUrl: "https://lh5.googleusercontent.com/p/AF1QipP_ZGOFScmSQM8ei2KnjJKhqfZ12p_WrxYRm4ma=w408-h270-k-no",
        imageHeight: 400,
        imageAlt: "อาคาร 63"
    });
});
