<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4">Freelancer Dashboard</h2>
        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="https://via.placeholder.com/150" alt="Freelancer Photo"
                            class="img-fluid rounded-circle mb-3"
                            style="width: 200px; height: 200px; object-fit: cover;">
                        <h5>John Doe</h5>
                        <p class="text-muted">Web Developer</p>
                        <a href="profile-settings.php" class="btn btn-outline-primary btn-sm w-100">Edit Profile</a>
                    </div>
                </div>
                <div class="list-group mt-4">
                    <a href="#" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="freelancer-orders.php" class="list-group-item list-group-item-action">Manage Orders</a>
                    <a href="earnings.php" class="list-group-item list-group-item-action">Earnings</a>
                    <a href="withdrawals.php" class="list-group-item list-group-item-action">Withdrawals</a>
                    <a href="profile-settings.php" class="list-group-item list-group-item-action">Settings</a>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-md-8">
                <!-- Overview -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5>Overview</h5>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4>$2,500</h4>
                                <p>Total Earnings</p>
                            </div>
                            <div class="col-md-4">
                                <h4>35</h4>
                                <p>Completed Orders</p>
                            </div>
                            <div class="col-md-4">
                                <h4>5</h4>
                                <p>Pending Orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Gigs -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5>My Gigs</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Gig Title</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Create a Responsive Website</td>
                                    <td>Web Development</td>
                                    <td>$500</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger">Deactivate</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Design a Logo</td>
                                    <td>Graphic Design</td>
                                    <td>$200</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger">Deactivate</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="add-offer.php" class="btn btn-outline-success">Create New Gig</a>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Recent Orders</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Client</th>
                                    <th>Gig</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1234</td>
                                    <td>Michael Scott</td>
                                    <td>Create a Landing Page</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>$300</td>
                                </tr>
                                <tr>
                                    <td>#1235</td>
                                    <td>Pam Beesly</td>
                                    <td>Design a Flyer</td>
                                    <td><span class="badge bg-warning text-dark">In Progress</span></td>
                                    <td>$150</td>
                                </tr>
                            </tbody>
                        </table>
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