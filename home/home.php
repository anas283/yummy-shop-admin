<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./home.js?v=<?php echo time(); ?>">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Icons -->
    <title>Home</title>
</head>
<body id="body">

    <div id="mySidenav" class="sidenav">
        <div>
            <a class="logo" href="../home/home.php">
                <img class="shop-logo" src="../images/yummy-logo.png" alt="">
            </a>
        </div>
        <div class="navs">
            <a href="../home/home.php" id="overview-tab" class="row nav-item tablinks active">
                <div>
                    <ion-icon class="ion-icon logo-active" name="home-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-active">Overview</p>
                </div>
            </a>
            <a href="../orders/orders.php" id="order" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="cart-outline"></ion-icon>
                </div>
                <div>
                    <p>Orders</p>
                </div>
            </a>
            <a href="#" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="bag-outline"></ion-icon>
                </div>
                <div>
                    <p>Products</p>
                </div>
            </div>
            <a href="../customers/customers.php" class="row nav-item tablinks">
                <div>
                    <ion-icon class="ion-icon" name="person-outline"></ion-icon>
                </div>
                <div>
                    <p>Customers</p>
                </div>
            </a>
        </div>
    </div>

    <section id="main">
        <nav class="main-nav">
            <div class="nav-item justify-content-end">
                <div class="custom-select" onclick="selectMenu()">
                    <select name="profile" id="profile-menu">
                        <option value="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></option>
                        <option value="change-pass">Change password</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>
            </div>
        </nav>
        <div id="overview" class="tabcontent">
            <h3>Overview</h3>
			<div class="div-line"></div>

			<!-- Top card-->
            <div class="row mt-20">
				<div class="col-3 mr-10">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col-6">
									<h6 class="text-secondary font-weight-medium">TOTAL TRAFFIC</h6>
									<h3 class="text-number text-dark"><b>350,897</b></h3>
								</div>
								<div class="col-6">
									<div class="icon-div icon-red float-right">
										<ion-icon class="card-icon" name="speedometer-outline"></ion-icon>
									</div>
								</div>
							</div>
							<p class="text-percentage">
								<span class="text-small text-green"><ion-icon name="arrow-up-outline"></ion-icon> 3.48%</span>
								<small class="text-gray">Since last month</small>
							</p>
						</div>
					</div>
              	</div>
              	<div class="col-3 mr-10">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col-6">
									<h6 class="text-secondary font-weight-medium">NEW USERS</h6>
									<h3 class="text-number text-dark"><b>2,418</b></h3>
								</div>
								<div class="col-6">
									<div class="icon-div icon-orange float-right">
										<ion-icon class="card-icon" name="pie-chart-outline"></ion-icon>
									</div>
								</div>
							</div>
							<p class="text-percentage">
								<span class="text-small text-green"><ion-icon name="arrow-up-outline"></ion-icon> 14.2%</span>
								<small class="text-gray">Since last month</small>
							</p>
						</div>
                	</div>
				</div>
				<div class="col-3">
					<div class="card card-stats">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col-6">
									<h6 class="text-secondary font-weight-medium">SALES</h6>
									<h3 class="text-number text-dark"><b>RM 15,269.00</b></h3>
								</div>
								<div class="col-6">
									<div class="icon-div icon-green float-right">
										<ion-icon class="card-icon" name="server-outline"></ion-icon>
									</div>
								</div>
							</div>
								<p class="text-percentage">
									<span class="text-small text-green"><ion-icon name="arrow-up-outline"></ion-icon> 7.3%</span>
									<small class="text-gray">Since last month</small>
								</p>
							</div>
						</div>
					</div>
				</div>

			<!-- Page content -->
			<div class="row mt-20 card card-chart-1">
				<div id="bar" style="width: 550px; height: 400px;"></div>
				<!-- coding pada home.js -->
			</div>
			
			<!-- pie chart-->
			<div class="row mt-20">
				<div id="pie" style="width: 550px; height: 400px;"></div>
				<!-- coding pada home.js -->
			</div>

        </div>
    </section>

    <script src="./home.js?v=<?php echo time(); ?>"></script>
    <script src="../global.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>

</body>
</html>