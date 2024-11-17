<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Profile Dashboard</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <img src="../assets/images/CoverImage.jpg" alt="Profile Image"
                            class="img-fluid rounded-circle mb-3" style="width: 200px; height: 200px;">
                        <h5 class="card-title">Mikel</h5>
                        <p class="text-muted">@kaellism4real</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="bi bi-geo-alt me-1"></i> Located in Indonesia</li>
                            <li><i class="bi bi-calendar me-1"></i> Joined in November 2024</li>
                            <li><i class="bi bi-globe me-1"></i> English (Native/Bilingual)</li>
                            <li><i class="bi bi-clock me-1"></i> Preferred working hours: 9AM-5PM</li>
                        </ul>
                        <a href="public-profile.php" class="btn btn-outline-primary btn-sm w-100 mb-2">View Public
                            Profile</a>
                        <a href="freelancers-profile.php" class="btn btn-primary btn-sm w-100">Switch to Freelancer
                            Dashboard</a>
                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-md-8">
                <div class="alert alert-primary alert-dismissible fade show shadow-sm" role="alert">
                    <strong>Welcome back, Mikel!</strong> Update your profile to attract freelancers.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>This is your profile when ordering services.</strong> For your freelancer profile, click
                    <a href="freelancers-profile.php">here.</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <!-- Profile Overview -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Profile Overview</h5>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4>15</h4>
                                <p class="text-muted">Completed Projects</p>
                            </div>
                            <div class="col-md-4">
                                <h4>4.8</h4>
                                <p class="text-muted">Average Rating</p>
                            </div>
                            <div class="col-md-4">
                                <h4>$5,200</h4>
                                <p class="text-muted">Total Spent</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Checklist -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Profile Checklist</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 70%;"
                                aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">70% Complete</div>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success me-2"></i> Upload Profile Picture
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success me-2"></i> Set Communication Preferences
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-x-circle text-danger me-2"></i> Add Payment Method
                            </li>
                        </ul>
                        <a href="profile-settings.php" class="btn btn-outline-secondary mt-3">Complete Profile</a>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activities</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="bi bi-briefcase me-2"></i> Hired *Jane Smith* for Logo Design - <span
                                    class="text-muted">2 days ago</span>
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-chat-dots me-2"></i> Messaged *Alex Brown* about project details - <span
                                    class="text-muted">3 hours ago</span>
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-credit-card me-2"></i> Paid $500 for Web Development Project - <span
                                    class="text-muted">Last week</span>
                            </li>
                        </ul>
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