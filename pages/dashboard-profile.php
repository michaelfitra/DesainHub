<?php
include '../includes/header.php';

// session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../pages/masuk.php");
//     exit;
// }

// // Fetch user data from the database
// $user_id = $_SESSION['user_id'];
// $query = "SELECT * FROM users WHERE id = ?";
// $stmt = $pdo->prepare($query);
// $stmt->execute([$user_id]);
// $user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Profile</title>
</head>

<body>
    <div class="container mt-5" style="height: 80%;">
        <div class="row">
            <!-- Left Section (Profile Details) -->
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="../assets/images/CoverImage.jpg" class="card-img-top rounded-circle mx-auto mt-4"
                        alt="Profile Image" style="width: 100px; height: 100px;">
                    <div class="card-body">
                        <h5 class="card-title">Mikel</h5>
                        <p class="card-text text-muted">@kaellism4real</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-geo-alt"></i> Located in Indonesia</li>
                            <li><i class="bi bi-calendar"></i> Joined in November 2024</li>
                            <li><i class="bi bi-globe"></i> English (Native/Bilingual)</li>
                            <li><i class="bi bi-clock"></i> Preferred working hours</li>
                        </ul>
                        <a href="#" class="btn btn-outline-primary w-100 mb-2">Preview Public Profile</a>
                        <a href="#" class="btn btn-primary w-100">Explore Fiverr</a>
                    </div>
                </div>
            </div>

            <!-- Right Section (Profile Checklist and Reviews) -->
            <div class="col-md-8">
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>This is your profile when ordering services.</strong> For your freelancer profile, click
                    <a href="">here.</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <h2>Hi 👋 Let's help freelancers get to know you</h2>
                <p>Get the most out of Fiverr by sharing a bit more about yourself and how you prefer to work with
                    freelancers.</p>

                <!-- Profile Checklist -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Profile Checklist</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50"
                                aria-valuemin="0" aria-valuemax="100">50%</div>
                        </div>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">Share how you plan to use
                                Fiverr</a>
                            <a href="#" class="list-group-item list-group-item-action">Set your communication
                                preferences</a>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reviews from freelancers</h5>
                        <p class="text-muted">kaellism4real doesn't have any reviews yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.js"></script>

</body>

</html>