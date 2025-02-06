

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="new_styles.css">
    <link rel="stylesheet" href="">

</head>
<body>
<?php include 'inc/nav.php'; ?>

   <!--  <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Visitor Management System</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Add Visitor</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Checked Out Visitors</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">View Data</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav> -->
    
     <div class="container-fluid mt-6">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Check Out Visitor</h5>
                        <div class="mb-3">
                            <form action="" method="post">
                            <label for="receipt-id" class="form-label">Receipt ID</label>
                            <input type="text" class="form-control mb-4" id="receipt-id" placeholder="Enter Receipt ID">
                            <button class="btn btn-primary">Checkout</button>
                        </form>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Details</h5>
                        <p class="card-text">Date: 2025-01-21</p>
                        <p class="card-text">Time in: 17:08:04</p>
                        <p class="card-text">Name: kishan</p>
                        <p class="card-text">Contact No: 9943454224</p>
                        <p class="card-text">Purpose: meeting</p>
                        <p class="card-text">Meeting: leader</p>
                        <p class="card-text">Receipt ID: 322870</p>
                        <p class="card-text">Comment: commenr</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Visitors</h5>
                        <p class="card-text">kisan</p>
                        <p class="card-text">kishan</p>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
