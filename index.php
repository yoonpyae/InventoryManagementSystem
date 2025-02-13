<?php 
session_start();
include('connect.php');

if(!isset($_SESSION['EmployeeID'])) 
{
	echo "<script>window.alert('Please Login first to open staff home.')</script>";
	echo "<script>window.location='login.php'</script>";

}else {
  $userID = $_SESSION['EmployeeID'];
  $Username = $_SESSION['Username'];
  $position = $_SESSION['Position'];
}

$query = "SELECT * FROM employee 
        WHERE EmployeeID='$userID'";
$ret = mysqli_query($connection, $query);
$arr = mysqli_fetch_array($ret);

// Query to get total customers
$customerQuery = "SELECT COUNT(*) AS totalCustomers FROM customer";
$customerResult = mysqli_query($connection, $customerQuery);
$customerData = mysqli_fetch_assoc($customerResult);
$totalCustomers = $customerData['totalCustomers'];

// Query to get total products
$productQuery = "SELECT SUM(quantity) AS totalProducts FROM product";
$productResult = mysqli_query($connection, $productQuery);
$productData = mysqli_fetch_assoc($productResult);
$totalProducts = $productData['totalProducts'];

// Fetch total sales amount
$query_sales = "SELECT SUM(GrandTotal) AS total_sales FROM sale";
$result_sales = mysqli_query($connection, $query_sales);
$data_sales = mysqli_fetch_assoc($result_sales);
$total_sales = $data_sales['total_sales'];

// Fetch total purchases amount
$query_purchases = "SELECT SUM(GrandTotal) AS total_purchases FROM purchase";
$result_purchases = mysqli_query($connection, $query_purchases);
$data_purchases = mysqli_fetch_assoc($result_purchases);
$total_purchases = $data_purchases['total_purchases'];

// Fetch low stock products
$query_low_stock = "SELECT ProductID, ProductName, StockAlert, Quantity FROM product WHERE Quantity < 50";
$result_low_stock = mysqli_query($connection, $query_low_stock);
$low_stock_products = [];
while ($row = mysqli_fetch_assoc($result_low_stock)) {
    $low_stock_products[] = $row;
}

// Query for top-selling products (December)
$query_top_selling_december = "
  SELECT p.ProductID, p.ProductName, SUM(sd.Quantity) AS TotalSold
  FROM product p
  JOIN saledetail sd ON p.ProductID = sd.ProductID
  JOIN sale s ON s.SaleID = sd.SaleID
  WHERE MONTH(s.SaleDate) = 1 AND YEAR(s.SaleDate) = YEAR(CURDATE())
  GROUP BY p.ProductID, p.ProductName
  ORDER BY TotalSold DESC
  LIMIT 3";
  $result_top_selling_december = mysqli_query($connection, $query_top_selling_december);

  // Store the results in an array
  $top_selling_products_december = [];
  while ($row = mysqli_fetch_assoc($result_top_selling_december)) {
      $top_selling_products_december[] = $row;
  }

  // Query to fetch recent sales
  $query_recent_sales = "
  SELECT s.SaleID, c.CustomerName, w.WarehouseName, s.TotalQuantity, s.GrandTotal, s.SaleDate, s.Status
  FROM sale s
  JOIN customer c ON c.CustomerID=s.CustomerID
  JOIN warehouse w ON w.WarehouseID=s.WarehouseID
  ORDER BY SaleDate DESC
  LIMIT 3";
  $result_recent_sales = mysqli_query($connection, $query_recent_sales);

  // Store the results in an array
  $recent_sales = [];
  while ($row = mysqli_fetch_assoc($result_recent_sales)) {
    $recent_sales[] = $row;
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Aung Bi Win Rice Trading House - Dashboard</title>
    <link rel="shortcut icon" href="img/grain.svg" type="image/svg+xml" />
    <!-- Custom fonts for this template-->
    <link
      href="vendor/fontawesome-free/css/all.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet"
    />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
  </head>

  <body id="page-top">
  <form action="index.php" method="post">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Sidebar -->
      <ul
        class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
        id="accordionSidebar"
      >
        <!-- Sidebar - Brand -->
        <a
          class="sidebar-brand d-flex align-items-center justify-content-center"
          href="index.php"
        >
          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-seedling"></i>
          </div>
          <div class="sidebar-brand-text mx-3">
            Aung Bi Win <sup>Trading House</sup>
          </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0" />
        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a
          >
        </li>

        <li class="nav-item">
          <a class="nav-link" href="customer.php">
            <i class="fas fa-user"></i>
            <span>Customers</span></a
          >
        </li>

        <li class="nav-item">
          <a class="nav-link" href="supplier.php">
            <i class="fas fa-handshake"></i>
            <span>Suppliers</span></a
          >
        </li>

        <li class="nav-item">
          <a class="nav-link" href="warehouse.php">
            <i class="fas fa-warehouse"></i>
            <span>Warehouses</span></a
          >
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Management</div>

        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseProduct"
            aria-expanded="true"
            aria-controls="collapseTwo"
          >
            <i class="fas fa-box"></i>
            <span>Products</span>
          </a>
          <div
            id="collapseProduct"
            class="collapse"
            aria-labelledby="headingTwo"
            data-parent="#accordionSidebar"
          >
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Manage Products:</h6>
              <a class="collapse-item" href="createProduct.php"
                >Create Product</a
              >
              <a class="collapse-item" href="productList.php">All Products</a>
              <a class="collapse-item" href="categoryList.php"
                >All Categories</a
              >
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapsePurchase"
            aria-expanded="true"
            aria-controls="collapseUtilities"
          >
            <i class="fas fa-cart-arrow-down"></i>
            <span>Purchases</span>
          </a>
          <div
            id="collapsePurchase"
            class="collapse"
            aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar"
          >
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Manage Purchases:</h6>
              <a class="collapse-item" href="createPurchase.php"
                >Create Purchase</a
              >
              <a class="collapse-item" href="purchaseList.php"
                >All Purchases</a
              >
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseSlae"
            aria-expanded="true"
            aria-controls="collapseUtilities"
          >
            <i class="fas fa-shopping-cart"></i>
            <span>Slaes</span>
          </a>
          <div
            id="collapseSlae"
            class="collapse"
            aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar"
          >
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Manage Sales:</h6>
              <a class="collapse-item" href="createSale.php">Create Sale</a>
              <a class="collapse-item" href="saleList.php">All Sales</a>
            </div>
          </div>
        </li>
        <?php if ($position == 'Super Admin'): ?>
        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseTransfer"
            aria-expanded="true"
            aria-controls="collapseUtilities"
          >
            <i class="fas fa-arrows-alt-h"></i>
            <span>Transfer</span>
          </a>
          <div
            id="collapseTransfer"
            class="collapse"
            aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar"
          >
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Manage Trasfer:</h6>
              <a class="collapse-item" href="createTransfer.php"
                >Create Transfer</a
              >
              <a class="collapse-item" href="transferList.php"
                >All Transfers</a
              >
            </div>
          </div>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseDelivery"
            aria-expanded="true"
            aria-controls="collapseUtilities"
          >
          <i class="fas fa-truck"></i>
          <span>Delivery</span>
          </a>
          <div
            id="collapseDelivery"
            class="collapse"
            aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar"
          >
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Manage Delivery:</h6>
              <a class="collapse-item" href="createDelivery.php"
                >Create Delivery</a
              >
              <a class="collapse-item" href="deliveryList.php"
                >All Deliveries</a
              >
            </div>
          </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Reports</div>

        <li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#"
            data-toggle="collapse"
            data-target="#collapseReport"
            aria-expanded="true"
            aria-controls="collapsePages"
          >
            <i class="fas fa-fw fa-folder"></i>
            <span>Reports</span>
          </a>
          <div
            id="collapseReport"
            class="collapse"
            aria-labelledby="headingPages"
            data-parent="#accordionSidebar"
          >
          <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="purchaseReport.php">Purchase Report</a>
              <a class="collapse-item" href="saleReport.php"
                >Sale Report</a
              >
            </div>
          </div>
        </li>
        <?php if ($position == 'Super Admin'): ?>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block" />

        <li class="nav-item">
          <a class="nav-link" href="users.php">
            <i class="fas fa-handshake"></i>
            <span>Employee</span></a
          >
        </li>
        <?php endif; ?>
      </ul>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <!-- Topbar -->
          <nav
            class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
          >
            <!-- Sidebar Toggle (Topbar) -->
            <button
              id="sidebarToggleTop"
              class="btn btn-link d-md-none rounded-circle mr-3"
            >
              <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Nav Item - Search Dropdown (Visible Only XS) -->
              <li class="nav-item dropdown no-arrow d-sm-none">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="searchDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Messages -->
                <div
                  class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                  aria-labelledby="searchDropdown"
                >
                  <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control bg-light border-0 small"
                        placeholder="Search for..."
                        aria-label="Search"
                        aria-describedby="basic-addon2"
                      />
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                          <i class="fas fa-search fa-sm"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </li>

              <!-- Nav Item - Alerts -->
              <li class="nav-item dropdown no-arrow mx-1">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="alertsDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="fas fa-bell fa-fw"></i>
                  <!-- Counter - Alerts -->
                  <span class="badge badge-danger badge-counter">3+</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div
                  class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                  aria-labelledby="alertsDropdown"
                >
                  <h6 class="dropdown-header">Alerts Center</h6>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                      <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">December 12, 2024</div>
                      <span class="font-weight-bold"
                        >A new monthly report is ready to download!</span
                      >
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                      <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">December 7, 2024</div>
                      $290.29 has been deposited into your account!
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                      <div class="icon-circle bg-warning">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">December 2, 2024</div>
                      Spending Alert: We've noticed unusually high spending for
                      your account.
                    </div>
                  </a>
                  <a
                    class="dropdown-item text-center small text-gray-500"
                    href="#"
                    >Show All Alerts</a
                  >
                </div>
              </li>

              <!-- Nav Item - Messages -->
              <li class="nav-item dropdown no-arrow mx-1">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="messagesDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="fas fa-envelope fa-fw"></i>
                  <!-- Counter - Messages -->
                  <span class="badge badge-danger badge-counter">7</span>
                </a>
                <!-- Dropdown - Messages -->
                <div
                  class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                  aria-labelledby="messagesDropdown"
                >
                  <h6 class="dropdown-header">Message Center</h6>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img
                        class="rounded-circle"
                        src="img/undraw_profile_1.svg"
                        alt="..."
                      />
                      <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                      <div class="text-truncate">
                        Hi there! I am wondering if you can help me with a
                        problem I've been having.
                      </div>
                      <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img
                        class="rounded-circle"
                        src="img/undraw_profile_2.svg"
                        alt="..."
                      />
                      <div class="status-indicator"></div>
                    </div>
                    <div>
                      <div class="text-truncate">
                        I have the photos that you ordered last month, how would
                        you like them sent to you?
                      </div>
                      <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img
                        class="rounded-circle"
                        src="img/undraw_profile_3.svg"
                        alt="..."
                      />
                      <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                      <div class="text-truncate">
                        Last month's report looks great, I am very happy with
                        the progress so far, keep up the good work!
                      </div>
                      <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                    </div>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                      <img
                        class="rounded-circle"
                        src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                        alt="..."
                      />
                      <div class="status-indicator bg-success"></div>
                    </div>
                    <div>
                      <div class="text-truncate">
                        Am I a good boy? The reason I ask is because someone
                        told me that people say this to all dogs, even if they
                        aren't good...
                      </div>
                      <div class="small text-gray-500">
                        Chicken the Dog · 2w
                      </div>
                    </div>
                  </a>
                  <a
                    class="dropdown-item text-center small text-gray-500"
                    href="#"
                    >Read More Messages</a
                  >
                </div>
              </li>

              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="userDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small"
                    ><?php echo $arr['EmployeeName'] ?></span
                  >
                  <img
                    class="img-profile rounded-circle"
                    src="<?php echo $arr['ProfilePicture'] ?>"
                  />
                </a>
                <!-- Dropdown - User Information -->
                <div
                  class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                  aria-labelledby="userDropdown"
                >
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                  </a>
                  <div class="dropdown-divider"></div>
                  <a
                    class="dropdown-item"
                    href="#"
                    data-toggle="modal"
                    data-target="#logoutModal"
                  >
                    <i
                      class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                    ></i>
                    Logout
                  </a>
                </div>
              </li>
            </ul>
          </nav>
          <!-- End of Topbar -->

          <!-- Begin Page Content -->
          <div class="container-fluid">
            <!-- Page Heading -->
            <div
              class="d-sm-flex align-items-center justify-content-between mb-4"
            >
              <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
             
            </div>
            <?php if ($position == 'Super Admin'): ?>
            <!-- Content Row -->
            <div class="row">
              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <a href="purchaseList.php" class="text-decoration-none">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2" >
                        <div
                          class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                        >
                          Purshases
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($total_purchases); ?> MMK
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                  <a href="saleList.php" class="text-decoration-none">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-success text-uppercase mb-1"
                        >
                          Sales
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($total_sales); ?>MMK
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Earnings (Monthly) Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                  <a href="productList.php" class="text-decoration-none">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-info text-uppercase mb-1"
                        >
                          rice bags
                        </div>
                        <div class="row no-gutters align-items-center">
                          <div class="col-auto">
                            <div id="totalProducts"
                              class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                            >
                            <?php echo $totalProducts; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i
                          class="fas fa-clipboard-list fa-2x text-gray-300"
                        ></i>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>
              </div>

              <!-- Pending Requests Card Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                  <a href="customer.php" class="text-decoration-none">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div
                          class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                        >
                          Customers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalCustomers">
                        <?php echo $totalCustomers; ?>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>
              </div>
            </div>
      

            <!-- Content Row -->

            <div class="row">
              <!-- Area Chart -->
              <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                  <!-- Card Header - Dropdown -->
                  <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                  >
                    <h6 class="m-0 font-weight-bold text-primary">
                    Recent Sales
                    </h6>
                  </div>
                  <!-- Card Body -->
                  <div class="card-body">
                  <div class="table-responsive">
                  <?php if (count($recent_sales) > 0): ?>
                      <table
                        class="table table-bordered"
                        id="dataTable"
                        width="100%"
                        cellspacing="0"
                      >
                        <thead>
                          <tr>
                            <th>SaleID</th>
                            <th>Sale Date</th>
                            <th>Customer</th>
                            <th>Warehouse</th>
                            <th>Status</th>
                            <th>Grand Total</th>
                            <th>Total Qty</th>
                          </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($recent_sales as $sale): ?>
                          <tr>
                            <td><?php echo $sale['SaleID']; ?></td>
                            <td><?php echo $sale['SaleDate']; ?></td>
                            <td><?php echo $sale['CustomerName']; ?></td>
                            <td><?php echo $sale['WarehouseName']; ?></td>
                            <td><span class="badge rounded-pill text-primary border border-primary"><?php echo $sale['Status']; ?></span></td>
                            <td><?php echo $sale['GrandTotal']; ?></td>
                            <td><?php echo $sale['TotalQuantity']; ?></td>                          
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      <?php else: ?>
                        <p class="text-danger">No recent sales found.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pie Chart -->
              <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                  <!-- Card Header - Dropdown -->
                  <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                  >
                    <h6 class="m-0 font-weight-bold text-primary">
                      Top Selling Products (2024)
                    </h6>
                    
                  </div>
                  <!-- Card Body -->
                  <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                      <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                      <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> ShweBo
                      </span>
                      <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> PawSan
                      </span>
                      <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> SinThwe
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Content Row -->
            <div class="row">
              <!-- Content Column -->
              <div class="col-lg-8 mb-4">
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                      Stock Alert
                    </h6>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                    <?php if (count($low_stock_products) > 0): ?>
                      <table
                        class="table table-bordered"
                        id="dataTable"
                        width="100%"
                        cellspacing="0"
                      >
                        <thead>
                          <tr>
                            <th>Code</th>
                            <th>Product</th>
                        
                            <th>Quantity</th>
                            <th>Alert Quantity</th>
                          </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($low_stock_products as $product): ?>
                          <tr>
                            <td><?php echo $product['ProductID']; ?></td>
                            <td><?php echo $product['ProductName']; ?></td>
                            <td><?php echo $product['Quantity']; ?></td>
                            <td><span class="badge rounded-pill text-warning border border-warning"><?php echo $product['StockAlert']; ?></span></td>
                          
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      <?php else: ?>
                        <p class="text-danger">All products are sufficiently stocked.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 mb-4">
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">
                        Top Selling Products (January)
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                      <?php if (count($top_selling_products_december) > 0): ?>
                        <table
                          class="table table-bordered"
                          id="dataTable"
                          width="100%"
                          cellspacing="0"
                        >
                          <thead>
                            <tr>
                              <th>ProductID</th>
                              <th>Product</th>
                              <th>Total Sale</th>                           
                            </tr>
                          </thead>

                          <tbody>
                          <?php foreach ($top_selling_products_december as $product): ?>
                            <tr>
                              <td><?php echo $product['ProductID']; ?></td>
                              <td><?php echo $product['ProductName']; ?></td>
                              <td><?php echo $product['TotalSold']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                        <?php else: ?>
                          <p class="text-danger">No sales data available for January.</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
      </div>
      <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div
      class="modal fade"
      id="logoutModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button
              class="close"
              type="button"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            Select "Logout" below if you are ready to end your current session.
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-secondary"
              type="button"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <a class="btn btn-primary" href="userLogout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
  </form>
</body>
</html>