
<?php
require_once __DIR__ . '/vendor/autoload.php';
@session_start();
@session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_token = $_POST['credential'];

    // สร้าง Google Client
    $client = new Google_Client();
    $client->setClientId('671830532557-vq7k3osqd0dnvrt3o8r7nlcv6kd5k5aq.apps.googleusercontent.com'); // ใส่ Google Client ID ของคุณ
    $client->setRedirectUri('http://localhost:8080/map_user.php'); // ตั้งค่าตรงนี้ให้ตรงกับที่ตั้งใน Google Cloud Console

    // ตรวจสอบ Token
    $payload = $client->verifyIdToken($id_token);

    if ($payload) {
        $userid = $payload['sub']; // Google ID ของผู้ใช้
        $email = $payload['email'];
        $name = $payload['name'];
        @$profile_picture = $payload['picture']; // URL ของภาพโปรไฟล์

        // เริ่มต้น Session และบันทึกข้อมูลผู้ใช้
        @session_start();
        $_SESSION['user'] = [
            'id' => $userid,
            'name' => $name,
            'email' => $email,
            'picture' => $profile_picture
        ];

        /*echo "<h1>Welcome, $name!</h1>";
        echo "<p>Your email is $email.</p>";
        echo "<img src='$profile_picture' alt='Profile Picture' style='width: 100px; height: 100px; border-radius: 50%;'>";*/
   
}
}
?>
<?php
@session_start(); // ควรเรียกใช้ก่อนการแสดงผล
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}


// ดึงข้อมูลเซสชัน
$profile_picture = $_SESSION['user']['picture'] ?? 'default_profile.png';
$name = $_SESSION['user']['name'] ?? 'Guest';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผนที่ NSM RUTS</title>
    <!-- Leaflet CSS -->
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
/* ตั้งค่าให้ทั้ง body และ html มีความสูงเต็มจอ */
html, body {
    height: 100%;
    margin: 0; /* ลบ margin ที่อาจจะมี */
}

/* ทำให้แผนที่เต็มหน้าจอ โดยหักความสูงของ Navigation */
#map {
    height: calc(100vh - 0vh); /* Sets the height of the map to 100% of the viewport height minus 38% */
    width: 100%;  /* The map will take up 100% of the screen width */
    z-index: 0; /* Ensures the map is behind other elements with a higher z-index */
    position: absolute; /* Positions the map absolutely within its container */
    top: 0em; /* Moves the map 3em down from the top of the container (to avoid overlapping with navigation) */
}
/* Container for the search box */
#search-box {
    position: absolute;
    top: 5%;
    left: 15px;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 10px; /* Space between input and search button */
    /*background: white;*/
    padding: 10px 15px;
    border-radius: 50px;
    /*box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);*/
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}


/* Wrapper for input and clear button */
.input-wrapper {
    position: relative;
    flex: 1; /* Allow the input to take available space */
    display: flex;
    align-items: center;
    transition: width 1s ease;
}



/* Clear button styling */
#clear-btn {
    position: absolute;
    right: 10px; /* Position inside the input field */
    width: 25px;
    height: 25px;
    background-color: transparent;
    color: #999;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s ease, transform 0.3s ease;
}

#clear-btn:hover {
    color: #f44336;
    transform: scale(1.2); /* Slight zoom effect */
}

/* Search button styling */
#search-btn {
    width: 40px;
    height: 40px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

#search-btn:hover {
    background-color: #45a049;
    transform: scale(1.1); /* Slight zoom effect */
}

/* Search button styling */
#back-btn {
    width: 40px;
    height: 40px;
    background-color:rgb(33, 110, 255);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

#back-btn:hover {
    background-color: rgb(0, 89, 255);
    transform: scale(1.1); /* Slight zoom effect */
}






ื/*nav*/
/* Reset styles */
* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* สไตล์สำหรับตัว navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color:rgba(20, 20, 20, 0.4); /* สีพื้นหลังของ navbar */
            padding: 10px 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 1em;
        }

        /* ชื่อแบรนด์ */
        .brand {
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

       /* รายการลิงก์ใน navbar */
.nav-links {
    display: flex;
    gap: 20px;
    justify-content: center; /* Centers the links horizontally */
    align-items: center; /* Centers the links vertically */
    text-align: center;
}

.nav-links a {
    text-decoration: none;
    color: white;
    font-size: 1rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-links a:hover {
    color: #ddd;
}

       
/* Move the zoom-in button to the bottom-right corner of the screen */
.leaflet-control-zoom-in,
.leaflet-control-zoom-out {
    position: fixed !important;
    bottom: 20px !important;  /* Adjust the value to move it up or down */
    right: 20px !important;   /* Adjust the value to move it left or right */
    z-index: 1000 !important; /* Ensure it's on top of other elements */
}

/* Optional: Space the zoom-in and zoom-out buttons apart */
.leaflet-control-zoom-in {
    margin-bottom: 50px; /* Adds space between zoom-in and zoom-out buttons */
}
/* Optional: Space the zoom-in and zoom-out buttons apart */
.leaflet-control-zoom-out {
    margin-bottom: 20px; /* Adds space between zoom-in and zoom-out buttons */
}

 /* Modern style for the zoom label */
 .ldmap-zoomlabel {
            position: fixed !important;
            bottom: 110px !important;
            right: 18px !important;
            z-index: 1000 !important;
            background-color: rgba(255, 255, 255); /* Slightly transparent background */
            color:rgb(0, 0, 0); /* White text for contrast */
            text-transform: uppercase;
            padding: 8px 8px; /* More padding for a cleaner look */
            border-radius: 50px; /* Rounded corners for a modern feel */
            font-size: 16px; /* Larger font size for readability */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Soft shadow for a floating effect */
            transition: all 0.3s ease-in-out; /* Smooth transition effect */
            font-weight: bold; /* Make the text bold */
        
        }

       /* กำหนดสไตล์สำหรับ tag list */
.tag-list {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.tag {
    position: relative;
    padding: 10px 20px;
    font-size: 16px;
    background-color: #3498db;
    color: white;
    border-radius: 20px;
    cursor: pointer;
    overflow: hidden;
    transition: background-color 0.3s;
}

.tag:hover {
    background-color: #2980b9;
}

/* สไตล์สำหรับ ripple effect */
.tag .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
}

/* การเคลื่อนไหว ripple effect */
@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

 


#search-input {
    width: 100%; /* ขยายให้เต็มความกว้างของพื้นที่ */
    padding: 10px 40px 10px 15px; /* เพิ่ม padding-right สำหรับปุ่มลบ */
    border: 1px solid #ddd;
    border-radius: 50px;
    font-size: 14px;
    outline: none;
    font-family: 'Prompt', sans-serif;
    transition: width 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
    box-sizing: border-box; /* ทำให้ padding ไม่ทำให้ขนาดเกิน */
}






@media (max-width: 769px) {
  
            .nav-links {
                display: none;
                flex-direction: column;
                background-color: #343435;
               
            }

            .nav-links.show {
                display: flex;
            }

            .hamburger {
                display: flex;
            }
                  
  

        }
/* Responsive Design */
@media (max-width: 768px) {
            nav {
                display: none;
            }
            .tag-list {
                display: none;
            }
            #back-btn {
                display: none;
            }
            .ldmap-zoomlabel {
    position: fixed; /* ให้ป้ายอยู่กับที่ */
   
}

    /* Container for the search box */
            #search-box {
            width: 100%;
            position: absolute;
            top: 1%;
            left: 0%;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 20px; /* Space between input and search button */
            /*background: white;*/
            border-radius: 50px;
            /*box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);*/
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            padding: 0 25px;
            -webkit-box-sizing: border-box;
            -webkit-box-shadow: 0 0 4px 1px rgba(0, 0, 0, .1);
        
}
        
        
        
    
         

            /* ขยายขนาดเมื่อโฟกัส */
            #search-input:focus {
                width: 99%; /* ขยายให้เต็มความกว้างของหน้าจอ */
            }

            /* ขยายขนาดเมื่อมีข้อความ */
            #search-input:not(:placeholder-shown) {
                width: 99%; /* ขยายให้เต็มความกว้างของหน้าจอ */
            }
    
    
    
    
    
   /* สไตล์สำหรับ search-box */


/* สไตล์สำหรับ input-wrapper */
.input-wrapper {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    
}

/* ปุ่ม hamburger */
#hamburger {
    cursor: pointer;
    width: 20px;
    height: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-right: 5px;
    position: absolute; /* วางให้ปุ่ม hamburger อยู่ในช่องค้นหา */
    left: 10px; /* ระยะห่างจากขอบซ้าย */
}

#hamburger div {
    width: 100%;
    height: 4px;
    background-color: #333;
}

/* ช่องค้นหาหรือ input */
#search-input {
    width: 100%;
    padding: 10px 40px 10px 40px;
    border: 1px solid #ddd;
    border-radius: 50px;
    font-size: 14px;
    outline: none;
    transition: width 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
    font-family: 'Prompt', sans-serif;
}


/* เมนูข้าง */
.side-menu {
    position: fixed;
    top: 0;
    left: -250px; /* ซ่อนเมนูเริ่มต้น */
    width: 250px;
    height: 100%;
    background-color: #333;
    color: white;
    transition: left 0.3s ease; /* เพิ่ม transition เพื่อให้การเปิดปิดดูนุ่มนวล */
    padding-top: 50px; /* ระยะห่างจากด้านบน */
    z-index: 1000; /* ให้เมนูแสดงอยู่ด้านบน */
}

/* เมนูข้างที่แสดง */
.side-menu.show {
    left: 0; /* แสดงเมนู */
}

.side-menu ul {
    list-style-type: none;
    padding: 0;
}

.side-menu ul li {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #444;
}

.side-menu ul li:hover {
    background-color: #555;
}

/* ปุ่มปิดเมนู */
#close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}



}
 
       

</style>

</head>
<body >
    <nav>
        <a href="map_user.php" class="brand">NSM RUTS</a>
        <div class="nav-links">
            <a href="#home">Dseboad</a>
            <a><?php echo $name ?? 'Guest'; ?></a>
            <a>
    <?php 
    if (!empty($profile_picture)) {
        echo "<img src='$profile_picture' alt='Profile Picture' style='width: 30px; height: 30px; border-radius: 50%;'>";
    } else {
        echo "<img src='default_profile.png' alt='Default Profile Picture' style='width: 30px; height: 30px; border-radius: 50%;'>";
    }
    ?>
</a>

            <a href="logout.php">
<svg fill="none" height="24" stroke-width="1.5" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M12 12H19M19 12L16 15M19 12L16 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 6V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>
</a>

        </div>
       
    </nav>
    
    <div id="map"></div>

    <div id="search-box">
   
    <div class="input-wrapper">
    <div class="hamburger" id="hamburger" >
        <div></div>
        <div></div>
        <div></div>
        </div>
        <input type="text"  id="search-input" placeholder="ค้นหา อาคาร,ห้อง">
        
        <button id="clear-btn" title="ลบข้อความ">✖</button>
        </div>
    
    <button id="search-btn" title="ค้นหา">🔍</button>
    <button id="back-btn" title="ย้อนกลับ">
    🧭
    </button>
    <div class="tag-list">
    <div class="tag" data-tag="Tag 1">🏫 อาคาร</div>
    <div class="tag" data-tag="Tag 2">🚗 ที่จอดรถ</div>
    <!--<div class="tag" data-tag="Tag 3">Tag 3</div>
    <div class="tag" data-tag="Tag 4">Tag 4</div>-->
</div>

</div>

<!-- เมนูที่ซ่อน -->
<div id="side-menu" class="side-menu" style="background-color: #f8f9fa; display: flex; flex-direction: column; align-items: center; gap: 20px;">
    <!-- ปุ่มปิดเมนู -->
    <button id="close-btn" title="ปิดเมนู" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 18px; cursor: pointer; width: 100%;">
        NSM RUTS
    </button>

    <!-- โปรไฟล์ -->
    <div style="display: flex; align-items: center; gap: 10px; margin-top: 20px;">
        <img src="<?php echo $profile_picture ?? 'default-profile.png'; ?>" 
             alt="Profile Picture" 
             style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #007bff;">
        <h4 style="margin: 0; font-size: 16px; color: #333;"><?php echo $name ?? 'Guest'; ?></h4>
    </div>

    <!-- เมนูรายการ -->
    <ul style="list-style: none; padding: 0; margin: 0; width: 100%;">
        <!-- เมนู Dashboard -->
        <li style="margin-bottom: 10px;">
            <a href="#home" style="display: flex; align-items: center; gap: 10px; padding: 10px 15px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333; transition: background-color 0.3s;">
                <svg fill="none" height="24" stroke-width="1.5" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12L12 3L21 12V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V12Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 21V12H15V21" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Dashboard
            </a>
        </li>

        <!-- เมนู Logout -->
        <li>
            <a href="logout.php" style="display: flex; align-items: center; gap: 10px; padding: 10px 15px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333; transition: background-color 0.3s;">
                <svg fill="none" height="24" stroke-width="1.5" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12H19M19 12L16 15M19 12L16 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19 6V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Logout
            </a>
        </li>
    </ul>
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


        // Create the zoom level label element
        var zoomLabel = document.createElement('div');
        zoomLabel.className = 'ldmap-zoomlabel';
        zoomLabel.innerHTML = map.getZoom(); // Initial zoom level
        document.body.appendChild(zoomLabel);

        // Update the zoom label when zoom level changes
        map.on('zoomend', function() {
            zoomLabel.innerHTML = map.getZoom();
        });

        var buildings = [
            { id: 1, name: "อาคาร 63", coords: [7.203827, 100.600637], rooms: ["63202", "63204", "ห้องประชุม1"] },
            { id: 2, name: "อาคาร 51", coords: [7.203623, 100.599763], rooms: ["63201", "ห้องต้า"] },
            { id: 3, name: "อาคาร 4", coords: [7.203322, 100.599197], rooms: [] },
            { id: 4, name: "อาคาร 32", coords: [7.203970, 100.601774], rooms: [] }
            // เพิ่มข้อมูลอาคารได้ตามต้องการ
        ];

// ตัวแปรเก็บตำแหน่งเริ่มต้นของแผนที่
var initialCoords = [7.2011801462897465, 100.6027025341781]; // สามารถปรับตามตำแหน่งเริ่มต้นของแผนที่
var initialZoom = 17; // ซูมระดับเริ่มต้น

// ปุ่มย้อนกลับ
document.getElementById('back-btn').addEventListener('click', function() {
    map.flyTo(initialCoords, initialZoom, { duration: 1 });
    if (currentMarker) {
        map.removeLayer(currentMarker); // ลบ marker ถ้ามี
        document.getElementById('search-input').value = ''; // ลบข้อความในช่องค้นหา
    }
});
// ฟังก์ชันค้นหา
function searchHandler() {
    var searchValue = document.getElementById('search-input').value.trim();
    if (!searchValue) {
        alert("กรุณาพิมพ์ชื่ออาคารหรือห้องที่ต้องการค้นหา");
        return;
    }

    var foundLocation = null;
    buildings.forEach(function(building) {
        if (building.name.includes(searchValue)) {
            foundLocation = { type: "building", coords: building.coords, name: building.name };
        }
        building.rooms.forEach(function(room) {
            if (room === searchValue) {
                foundLocation = { type: "room", coords: building.coords, room: room, name: building.name };
            }
        });
    });

    if (foundLocation) {
        displayLocation(foundLocation);
    } else {
        alert("ไม่พบอาคารหรือห้องที่ค้นหา");
    }
}

// เพิ่ม Event Listener สำหรับปุ่ม Enter
document.getElementById('search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { // ตรวจสอบว่าปุ่มที่กดคือ Enter
        e.preventDefault(); // ป้องกันการส่งฟอร์ม (ถ้ามี)
        searchHandler(); // เรียกฟังก์ชันค้นหา
    }
});

// เพิ่ม Event Listener สำหรับปุ่มค้นหา
document.getElementById('search-btn').addEventListener('click', searchHandler);



// ฟังก์ชันแสดงตำแหน่ง
function displayLocation(location) {
    map.flyTo(location.coords, 19, { duration: 1 });
    if (currentMarker) {
        map.removeLayer(currentMarker);
    }
    currentMarker = L.marker(location.coords).addTo(map)
        .bindPopup(location.type === "building" ? location.name : `ห้อง: ${location.room} ใน ${location.name}`)
        .openPopup();
}

// ฟังก์ชันลบข้อความในช่องค้นหา
document.getElementById('clear-btn').addEventListener('click', function() {
    document.getElementById('search-input').value = ''; // ลบข้อความในช่องค้นหา
    document.getElementById('search-input').focus(); // โฟกัสที่ช่องค้นหา
});


// ฟังก์ชันสำหรับการสร้าง ripple effect
document.querySelectorAll('.tag').forEach(function(tag) {
    tag.addEventListener('click', function(e) {
        // สร้าง ripple element
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        
        // กำหนดขนาดและตำแหน่งของ ripple
        const rect = tag.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = `${size}px`;
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        
        // เพิ่ม ripple element ไปที่ tag
        tag.appendChild(ripple);
        
        // ลบ ripple หลังจาก animation เสร็จ
        ripple.addEventListener('animationend', function() {
            ripple.remove();
        });
    });
});


        // ตัวแปรสำหรับจัดการ Marker
        var currentMarker = null;

// ฟังก์ชันสำหรับจับตำแหน่ง GPS และแสดงตำแหน่งปัจจุบัน
function locateUser() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            function(position) {
                const userCoords = [position.coords.latitude, position.coords.longitude];

                // แสดงตำแหน่งผู้ใช้บนแผนที่
                if (!userMarker) {
                    userMarker = L.marker(userCoords, {
                        icon: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
                            iconSize: [25, 25]
                        })
                    }).addTo(map).bindPopup("คุณอยู่ที่นี่").openPopup();
                } else {
                    userMarker.setLatLng(userCoords);
                }

                // เลื่อนแผนที่ไปยังตำแหน่งผู้ใช้
                map.setView(userCoords, 17);
            },
            function(error) {
                console.error("ไม่สามารถจับตำแหน่งได้:", error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        alert("อุปกรณ์ของคุณไม่รองรับการจับตำแหน่ง");
    }
}

// ตัวแปรสำหรับจัดการตำแหน่งผู้ใช้
var userMarker = null;

// เรียกใช้ฟังก์ชัน locateUser() เมื่อโหลดหน้าเว็บ
locateUser();



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
</head>





<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>


<script>
// ค้นหาปุ่ม hamburger และเมนูข้าง
const hamburger = document.getElementById('hamburger');
const sideMenu = document.getElementById('side-menu');
const closeBtn = document.getElementById('close-btn');

// เมื่อคลิกที่ปุ่ม hamburger ให้เปิด/ปิดเมนู
hamburger.addEventListener('click', function() {
    sideMenu.classList.toggle('show'); // สลับการแสดงผลเมนู
});

// เมื่อคลิกที่ปุ่มปิดเมนู
closeBtn.addEventListener('click', function() {
    sideMenu.classList.remove('show'); // ปิดเมนู
});

// เมื่อคลิกที่พื้นที่นอกเมนู (แถวอื่นๆ ของหน้า)
document.addEventListener('click', function(event) {
    if (!sideMenu.contains(event.target) && !hamburger.contains(event.target)) {
        sideMenu.classList.remove('show'); // ปิดเมนูถ้าคลิกข้างนอก
    }
});

</script>
<script>
    // ดึง Element
const searchInput = document.getElementById('search-input');
const searchBtn = document.getElementById('search-btn');

// ซ่อนปุ่มค้นหาเริ่มต้น
searchBtn.style.display = 'none';

// แสดงปุ่มเมื่อโฟกัสที่ช่องค้นหา
searchInput.addEventListener('focus', function() {
    searchBtn.style.display = 'inline-block'; // หรือ 'block' ตามการออกแบบ
});

// ซ่อนปุ่มเมื่อสูญเสียโฟกัส
searchInput.addEventListener('blur', function() {
    if (!searchInput.value.trim()) { // ซ่อนเฉพาะถ้าไม่มีข้อความในช่องค้นหา
        searchBtn.style.display = 'none';
    }
});

</script>









</body>
</html>
