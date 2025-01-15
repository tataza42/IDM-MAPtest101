<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.7/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            overflow: hidden;
        }

        .container {
    display: flex;
    justify-content: center;
    align-items: stretch; /* ให้การ์ดยืดตามความสูง */
    flex-wrap: wrap;
    gap: 0; /* ลบช่องว่างระหว่างการ์ด */
    width: 100%;
    max-width: 1200px;
    height: auto; /* ความสูงอัตโนมัติ */
    min-height: 500px;
}

.login-card,
.side-card {
    background: #fff;
    padding: 40px;
    flex: 1; /* ให้การ์ดยืดเต็มพื้นที่เท่ากัน */
    max-width: 380px;
    height: auto; /* ใช้ความสูงอัตโนมัติ */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
 
    margin: 0; /* ลบระยะขอบด้านนอก */
}

/* การ์ดบน */
.login-card {
    border-radius: 16px 0px 0px 16px;
}

/* การ์ดล่าง */
.side-card {
    border-radius: 0px 16px 16px 0px;
}

.login-card {
    display: flex;
    flex-direction: column; /* ให้เนื้อหาภายในเรียงเป็นแนวตั้ง */
    justify-content: space-between; /* จัดระเบียบเนื้อหา */
}

        .login-card h2 {
            font-size: 2rem;
            color: #0044cc;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .login-card h5 {
            font-size: 0.8rem;
            color:rgb(0, 0, 0);
            font-weight: bold;
            text-align: left;
        }

        .login-card input {
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccd7e0;
            font-size: 1rem;
            width: 100%;
            border-radius: 8px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .login-card input:focus {
            border-color: #0044cc;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 68, 204, 0.2);
        }

        .login-card button {
            width: 100%;
            padding: 14px;
            background: #0044cc;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 15px;
        }

        .login-card button:hover {
            background: #0033aa;
            transform: translateY(-2px);
        }

        .login-card .forgot-password {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #0044cc;
            text-decoration: none;
        }

        .google-signin-container {
            margin-top: 20px;
            text-align: center;
        }

        .google-signin-container h3 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .g_id_signin {
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
        }

        .sign-in-seperator {
            text-align: center;
            color: #bbb;
            position: relative;
            margin: 30px 0 20px;
        }

        .sign-in-seperator span {
            background: #fff;
            padding: 0 10px;
            font-size: 14px;
            z-index: 1;
        }

        .sign-in-seperator::before,
        .sign-in-seperator::after {
            content: "";
            position: absolute;
            width: 25%;
            height: 1px;
            background: #ddd;
            top: 50%;
            z-index: 0;
        }

        .sign-in-seperator::before {
            left: 0;
        }

        .sign-in-seperator::after {
            right: 0;
        }

        /* Side Card Styles */
        .side-card img {
    width: 100%; /* ให้ภาพเต็มความกว้างของ container */
    max-width: 800px; /* จำกัดความกว้าง */
    height: auto; /* รักษาอัตราส่วนของภาพ */
    display: block;
    margin: 0 auto;
}


        .side-card p {
            margin-top: 10px;
            color: #666;
            font-size: 1rem;
        }

       /* ปรับสำหรับมือถือ (หน้าจอเล็กกว่า 768px) */
@media (max-width: 768px) {
    .container {
        flex-direction: column; /* เรียงการ์ดในแนวตั้ง */
        gap: 20px; /* ระยะห่างระหว่างการ์ด */
        justify-content: center; /* จัดให้อยู่ตรงกลาง */
    }

    /* ซ่อนการ์ดที่สอง */
    .side-card {
        display: none;
    }

    /* จัดการ์ดล็อกอินให้อยู่ตรงกลาง */
    .login-card {
        max-width: 100%; /* ให้เต็มหน้าจอ */
        margin: 0 auto; /* กึ่งกลางแนวนอน */
        border-radius: 16px;
    }
}
/* ปรับปุ่มให้ดูดี */
button {
    position: relative;
    width: 100%;
    padding: 14px;
    background: #0044cc;
    color: white;
    font-size: 1.1rem;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}
button:hover {
    background: #0033aa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

button .spinner {
    position: absolute;
    left: 25%; /* วาง spinner ตรงกลาง */
    transform: translateX(-50%) translateY(-50%); /* ใช้ translate เพื่อย้าย */
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
    display: none;
}
/* สไตล์การหมุนของ spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ปรับแต่งปุ่มเมื่อกำลังโหลด */
button.loading {
    background: #666; /* เปลี่ยนสีพื้นหลังเมื่อกำลังโหลด */
    pointer-events: none; /* ป้องกันการคลิกขณะกำลังโหลด */
}

/* ซ่อนข้อความในปุ่มขณะกำลังโหลด */
button.loading .button-text {
    visibility: hidden;
}

/* แสดง spinner เมื่อกำลังโหลด */
button.loading .spinner {
    display: block;
}

/* ปรับขนาดของข้อความในปุ่ม */
button .button-text {
    visibility: visible; /* ให้ข้อความยังคงแสดง */
}


    </style>
</head>
<body>

    <div class="container">
        <!-- Login Card -->
        <div class="login-card">
            <!-- Google Sign-In -->
            <div class="google-signin-container">
                <div id="g_id_onload"
                     data-client_id="671830532557-vq7k3osqd0dnvrt3o8r7nlcv6kd5k5aq.apps.googleusercontent.com"
                     data-context="signin"
                     data-ux_mode="popup"
                     data-login_uri="http://localhost:8080/map_user.php"
                     data-auto_prompt="false">
                </div>

                <div class="g_id_signin"
                     data-type="standard"
                     data-shape="rectangular"
                     data-theme="outline"
                     data-text="signin_with"
                     data-size="large"
                     data-logo_alignment="left">
                </div>
            </div>

            <div class="sign-in-seperator">
                <span>or Sign in with Email</span>
            </div>

            <!-- Database Login -->
            <form id="loginForm" action="login.php" method="POST">
                <h5>Username (e-Passport)</h5>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
                <h5>Password</h5>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <button type="submit" id="loginButton">
    <span>Login</span>
    <div class="spinner"></div>
</button>
         

            </form>

            <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <!-- Side Card -->
        <div class="side-card">
            <img src="https://via.placeholder.com/600x400" alt="Placeholder Image">
            <h3>ระบบค้นหาอาหารหรือ</h3>
            <p>Sign in to access all our features and enjoy a seamless experience.</p>
        </div>
    </div>

    <script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();  // ป้องกันการส่งฟอร์มทันที

        // เปลี่ยนปุ่มเป็นสถานะกำลังโหลด
        var loginButton = document.getElementById("loginButton");
        loginButton.classList.add("loading");

        // เปลี่ยนข้อความในปุ่ม
        loginButton.innerHTML = '<div class="spinner"></div><span>Logging in...</span>';

        // หลังจากนั้นส่งฟอร์ม (สามารถใช้ setTimeout เพื่อจำลองการทำงาน)
        setTimeout(function() {
            // ส่งฟอร์มหลังจากการโหลด
            document.getElementById("loginForm").submit();
        }, 1500); // หน่วงเวลา 1.5 วินาทีเพื่อให้ผู้ใช้เห็นการโหลด
    });
</script>


    


</body>
</html>
