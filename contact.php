<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-danger">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="assets/LOGO.jpg" alt="" width="100" height="100" class="img-thumbnail">
                <p class="ms-5"><a href="index.php" style="color: aqua;">HOME.....</a></p>
                <p class="ms-5"><a href="about.php" style="color: aqua;">ABOUT US.....</a></p>
                <p class="ms-5"><a href="contact.php" style="color: aqua;">CONTACT US.....</a></p>
            </div>
        </div>
    </nav>

      <section id="contact" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center" style="color: brown;">Contact Us</h2>
            <form action="/customer" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" rows="3" name="pesan"></textarea>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">File</label>
                    <input type="file" class="form-control" name="assets">
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </section>

   

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2024 Kentucky Fried Chicken(KFC).
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
