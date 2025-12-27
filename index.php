<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FELUX - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">FELUX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Slider -->
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1920x600?text=Summer+Sale+-+Up+to+50%+Off" class="d-block w-100" alt="Summer Sale">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Summer Sale</h2>
                    <p>Get up to 50% off on all items!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1920x600?text=New+Arrivals" class="d-block w-100" alt="New Arrivals">
                <div class="carousel-caption d-none d-md-block">
                    <h2>New Arrivals</h2>
                    <p>Discover our latest collection.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1920x600?text=Free+Shipping" class="d-block w-100" alt="Free Shipping">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Free Shipping</h2>
                    <p>On all orders over $50.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Discount Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Special Offers</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/300x200?text=Product+1" class="card-img-top" alt="Product 1">
                        <div class="card-body">
                            <h5 class="card-title">Dextera Bangle</h5>
                            <p class="card-text">Octagon shape, White, Gold-tone plated</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">JD80</span>
                                <span class="badge bg-danger">-10%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/300x200?text=Product+2" class="card-img-top" alt="Product 2">
                        <div class="card-body">
                            <h5 class="card-title">Teddy Bracelet</h5>
                            <p class="card-text">Bear, Pink, Rose gold-tone plated</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">JD60</span>
                                <span class="badge bg-danger">-15%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/300x200?text=Product+3" class="card-img-top" alt="Product 3">
                        <div class="card-body">
                            <h5 class="card-title">Volta Bracelet</h5>
                            <p class="card-text">Bow, White, Rhodium plated</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">JD70</span>
                                <span class="badge bg-danger">-5%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 FELUX. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>