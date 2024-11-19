<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Name | Your Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <!-- Header -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Category Name</h1>
        <p class="lead">Find the best services for your needs in this category.</p>
    </header>

    <!-- Filter dan Sub-Category Navigation -->
    <div class="bg-light py-3">
        <div class="container">
            <div class="d-flex flex-nowrap overflow-auto">
                <button class="btn btn-outline-primary me-2">All</button>
                <button class="btn btn-outline-primary me-2">Sub-category 1</button>
                <button class="btn btn-outline-primary me-2">Sub-category 2</button>
                <button class="btn btn-outline-primary me-2">Sub-category 3</button>
            </div>
        </div>
    </div>

    <!-- Service Cards -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php include '../includes/card.php'; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="container">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>