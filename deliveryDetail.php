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

$DeliveryID=$_GET['DeliveryID'];

//Single-------------------------------
$Query1="SELECT da.*, e.Position,e.EmployeeName
		 FROM delivery da,employee e
		 WHERE da.DeliveryID='$DeliveryID'
		 AND da.EmployeeID=e.EmployeeID";
$result1=mysqli_query($connection,$Query1);
$arr1=mysqli_fetch_array($result1);

//Repete-------------------------------
$Query2="SELECT da.*, dad.*, s.SaleID,s.TotalQuantity,s.GrandTotal
		 FROM delivery da,deliverydetail dad,sale s
		 WHERE dad.DeliveryID='$DeliveryID'
		 AND da.DeliveryID=dad.DeliveryID
		 AND dad.SaleID=s.SaleID";
$result2=mysqli_query($connection,$Query2);
$count=mysqli_num_rows($result2);

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

    <title>Aung Bi Win Rice Trading House - All Purchases</title>
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

    <style>
      .info-section {
        margin-bottom: 20px;
      }
      .order-summary th,
      .order-summary td {
        vertical-align: middle;
        text-align: center;
      }
      .order-summary thead {
        background-color: #f8f9fa;
      }
    </style>

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />

    <link rel="stylesheet" href="css/animate.css" />

    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css" />

    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body id="page-top">
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
        <li class="nav-item">
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
          <a class="nav-link" href="index.php">
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

        <li class="nav-item ">
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

        <li class="nav-item ">
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
              <a class="collapse-item" href="slaeList.php">All Sales</a>
            </div>
          </div>
        </li>

        <?php if ($position == 'Super Admin'): ?>
        <li class="nav-item ">
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
              <a class="collapse-item" href="transferList.php">All Transfers</a>
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
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block" />
<?php if ($position == 'Super Admin'): ?>
        <li class="nav-item">
          <a class="nav-link" href="users.php">
            <i class="fas fa-users"></i>
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
              <h1 class="h3 mb-0 text-gray-800">Delivery Detail</h1>
            </div>
            <div class="page-wrapper">
              <div class="content">
                <div class="page-header">
                  <div class="page-title">
                    <h6>Manage your delivery detail</h6>
                  </div>
                </div>

                <form action="deliveryDetail.php" method="post">
                  <div class="card">
                    <div class="card-body">
                      <div class="mb-5">
                         <!-- Top Buttons -->
                         <div class="d-flex mb-3">
                        <button class="btn btn-outline-secondary me-2 mr-2">
                         <a href="deliveryList.php" class="text-decoration-none"><i class="fas fa-arrow-left"></i> All Deliveries</a> 
                          </button>
                          <?php echo "<a class='btn btn-purple me-2 mr-2' style='background-color: #9b59b6; color: white' href='deliveryDetail_pdf.php?DeliveryID=" . $arr1['DeliveryID'] . "'><i class='fas fa-file-pdf'></i> PDF</a>"; ?>                             
                        </div>

                        <!-- Delivery Details Header -->
                        <div class="text-center mt-5 mb-5">
                          <h3>Delivery Detail : <?php echo $arr1['DeliveryID'] ?></h3>
                        </div>

                        <!-- Info Sections -->
                        <div class="row info-section">                          
                          <div class="col-md-6">
                            <h5><strong>Employee Info</strong></h5>
                            <p>
                            <?php echo $arr1['EmployeeName'] ?><br />
                            <?php echo $arr1['Position'] ?>
                            </p>
                          </div>
                          <div class="col-md-6">
                            <h5><strong>Delivery Info</strong></h5>
                            <p>
                              Reference: <?php echo $arr1['DeliveryID'] ?><br />
                              Status:
                              <span class="badge bg-success"><?php echo $arr1['Status'] ?></span><br />
                              Note:
                              <span ><?php echo $arr1['Note'] ?></span>
                            </p>
                          </div>
                        </div>

                        <!-- Order Summary Table -->
                        <h5 class="mb-3"><strong>Delivery Summary</strong></h5>
                        <table class="table table-bordered order-summary">
                          <thead>
                            <tr>
                              <th>SaleID</th>
                              <th>Delivery Fee</th>
                              <th>Quantity</th>
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                        	<?php  
                          for ($y=0;$y<$count;$y++) 
                          { 
                            $arr2=mysqli_fetch_array($result2);

                            echo "<tr>";
                              echo "<td>" . $arr2['SaleID'] . "</td>";
                              echo "<td>" . $arr2['DeliveryFee'] . "</td>";
                              echo "<td>" . $arr2['Quantity'] . "</td>";
                              echo "<td>" . $arr2['Quantity'] * $arr2['DeliveryFee'] . "</td>";
                            echo "</tr>";
                          }
                          ?>
                          </tbody>
                        </table>

                        <!-- Summary Total -->
                        <div class="row justify-content-end">
                          <div class="col-md-4">
                            <table class="table table-borderless">
                              <tr>
                                <th>Total Quantity:</th>
                                <td><?php echo $arr1['TotalQuantity'] ?> bags</td>
                              </tr>
                              <tr>
                                <th>Total Amount:</th>
                                <td><?php echo $arr1['TotalAmount'] ?> MMK</td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
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
            <a class="btn btn-primary" href="login.php">Logout</a>
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
  </body>
</html>
