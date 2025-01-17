<?php
session_start();

// Fungsi untuk menghindari XSS dengan sanitasi input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $message = sanitize_input($_POST['message']);
    
    // Validasi file upload
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_error = $_FILES['file']['error'];

    // Pastikan file ada (jika tidak, beri pesan error)
    if (empty($file_name)) {
        $_SESSION['error_message'] = "Please attach a file.";
        header("Location: index.php?status=error");
        exit();
    }

    // Validasi ekstensi file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        $_SESSION['error_message'] = "File type not allowed!";
        header("Location: index.php?status=error");
        exit();
    }

    // Validasi ukuran file (maks 5MB)
    if ($file_size > 5 * 1024 * 1024) {
        $_SESSION['error_message'] = "File size exceeds the limit!";
        header("Location: index.php?status=error");
        exit();
    }

    // Tentukan folder tujuan
    $upload_dir = 'image_upload/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
    }

    // Tentukan path lengkap untuk file yang akan disimpan
    $file_destination = $upload_dir . basename($file_name);

    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($file_tmp, $file_destination)) {
        $_SESSION['success_message'] = "File successfully uploaded!";
        header("Location: index.php?status=success");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to upload file.";
        header("Location: index.php?status=error");
        exit();
    }
}
?>
