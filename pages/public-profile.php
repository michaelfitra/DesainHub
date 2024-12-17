<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Profile</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Link ke stylesheet Anda -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include '../includes/header.php'; ?> <!-- Header file -->

    <div class="container mt-4">
        <!-- Profile Section -->
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <img src="https://via.placeholder.com/200" alt="Freelancer Photo"
                            class="img-fluid rounded-circle mb-3"
                            style="width: 200px; height: 200px; object-fit: cover;">
                        <h3>Jane Doe</h3>
                        <p class="text-muted">UI/UX Designer</p>
                        <div class="rating">
                            <span class="text-warning">★★★★★</span>
                            <span>(120 reviews)</span>
                        </div>
                        <button class="btn btn-primary btn-sm mt-3">Contact</button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4>About Me</h4>
                        <p>
                            Hi, I'm Jane, a professional UI/UX designer with over 5 years of experience in creating
                            stunning user interfaces for web and mobile apps. I love working with clients to transform
                            ideas into visual masterpieces.
                        </p>
                        <hr>
                        <h4>Skills</h4>
                        <div>
                            <span class="badge bg-primary">Figma</span>
                            <span class="badge bg-secondary">Adobe XD</span>
                            <span class="badge bg-success">Sketch</span>
                            <span class="badge bg-warning text-dark">Wireframing</span>
                        </div>
                        <hr>
                        <h4>Portfolio</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/150" alt="Portfolio Item"
                                    class="img-fluid rounded">
                                <p class="text-center mt-2">E-commerce App</p>
                            </div>
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/150" alt="Portfolio Item"
                                    class="img-fluid rounded">
                                <p class="text-center mt-2">Landing Page Design</p>
                            </div>
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/150" alt="Portfolio Item"
                                    class="img-fluid rounded">
                                <p class="text-center mt-2">Social Media Dashboard</p>
                            </div>
                        </div>
                        <hr>
                        <h4>Pricing & Services</h4>
                        <ul>
                            <li><strong>Basic Package:</strong> $50 - Landing Page Design (3 days)</li>
                            <li><strong>Standard Package:</strong> $100 - Website Design (7 days)</li>
                            <li><strong>Premium Package:</strong> $200 - Full UI/UX Design (14 days)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?> <!-- Footer file -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.js"></script>

</body>

</html>