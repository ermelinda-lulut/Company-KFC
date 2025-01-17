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

    // Buat nama file unik
    $unique_file_name = uniqid() . '_' . $file_name;

    // Include Google Cloud Storage SDK
    require 'vendor/autoload.php'; 

    // Path ke kredensial service account
    $keyFilePath = '/var/www/html/key.json';

    // Membuat instansi client untuk Google Cloud Storage
    $storage = new \Google\Cloud\Storage\StorageClient([
        'keyFilePath' => $keyFilePath
    ]);

    // Tentukan nama bucket
    $bucketName = 'cvpengarep_bucket';

    // Dapatkan referensi ke bucket
    $bucket = $storage->bucket($bucketName);

    // Upload file ke bucket
    try {
        $object = $bucket->upload(
            fopen($file_tmp, 'r'),
            [
                'name' => $unique_file_name  
            ]
        );

        // Ambil URL file yang diupload
        $file_url = $object->info()['mediaLink'];

        // Koneksi ke database
        include('db.php');

        try {
            // Masukkan data ke database
            $stmt = $pdo->prepare("INSERT INTO orders (name, email, message, file_name, file_path) 
                                   VALUES (:name, :email, :message, :file_name, :file_path)");

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message,
                ':file_name' => $unique_file_name,
                ':file_path' => $file_url  
            ]);

            // Pesan sukses
            $_SESSION['success_message'] = "Your message has been successfully sent!";
            header("Location: index.php?status=success");
            exit();
        } catch (PDOException $e) {
            // Tangani error
            $error_message = "There was an error sending your message.";
            error_log("Error inserting data: " . $e->getMessage());
            $_SESSION['error_message'] = $error_message;
            header("Location: index.php?status=error");
            exit();
        }

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error uploading file to Google Cloud Storage: " . $e->getMessage();
        header("Location: index.php?status=error");
        exit();
    }
}
?>
