<?php
session_start();

// การเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "idm_ruts";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบเมื่อผู้ใช้ส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Query เพื่อตรวจสอบข้อมูลผู้ใช้
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // ตรวจสอบรหัสผ่าน
        if (hash('sha256', $input_password) == $user['password']) {
            $_SESSION['user'] = $user['username'];
            header("Location: map_user.php"); // เปลี่ยนไปหน้า Dashboard
            exit();
        } else {
            echo "<p class='error'>รหัสผ่านไม่ถูกต้อง</p>";
        }
    } else {
        echo "<p class='error'>ไม่พบผู้ใช้</p>";
    }
}
?>

