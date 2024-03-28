<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pengguna";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM data_pengguna WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        header("location: dashboard.html");
        exit;
    } else {
        $login_err = "Username atau password salah.";
    }
}


// Registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = $_POST["email"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];

    $stmt = $conn->prepare("INSERT INTO data_pengguna (username, password, email, address, phone) VALUES (:username, :password, :email, :address, :phone)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);

    if ($stmt->execute()) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        header("location: login-layout.html");
        exit;
    } else {
        $register_err = "Registrasi gagal. Silakan coba lagi.";
    }
}

// Lupa Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["forgot"])) {
    $email = $_POST["email"];
    $to = $email;
    $subject = "Reset Password";
    $message = "Silakan klik tautan berikut untuk mengatur ulang kata sandi Anda: <a href='https://domain.com/reset_password.php'>Reset Password</a>";
    $headers = "From: webmaster@example.com" . "\r\n" .
               "Content-Type: text/html; charset=UTF-8" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        $forgot_success = "Email berhasil dikirim. Silakan periksa kotak masuk Anda.";
    } else {
        $forgot_err = "Gagal mengirim email.";
    }
}
?>
