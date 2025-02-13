<?php 
session_start();
include('connect.php');
include('AutoID_Functions.php');
include('Transfer_Functions.php');

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

if(isset($_POST['btnSave'])) 
{
 // Check if products are added to the session
 if (!isset($_SESSION['Transfer_Functions']) || count($_SESSION['Transfer_Functions']) === 0) {
  echo "<script>
          alert('Error: No products added to the transfer. Please add at least one product before saving.');
          window.location='createTransfer.php';
        </script>";
  exit(); // Ensure script execution stops
}

    $txtTransferID = $_POST['txtTransferID'];
    $txtTransferDate = $_POST['txtTransferDate'];
    $fromWarehouseID = $_POST['fromWarehouseID'];
    $EmployeeID = $_SESSION['EmployeeID'];
    $txtTotalQuantity = $_POST['txtTotalQuantity'];
    $txtTotalAmount = $_POST['txtTotalAmount'];
    $txtNote = $_POST['txtNote'];
    $toWarehouseID = $_POST['toWarehouseID'];
    $cboStatus = $_POST['cboStatus'];

    // Check if the From and To warehouses are the same
    if ($fromWarehouseID === $toWarehouseID) {
      echo "<script>alert('Error: The source and destination warehouses cannot be the same!');</script>";
  } else {
        $Insert1 = "INSERT INTO `transfer`
                  (`TransferID`,`fromWarehouseID`, `toWarehouseID`, `TransferDate`, `EmployeeID`, `TotalQuantity`, `TotalAmount`, `Status`, `Note`) 
                  VALUES
                  ('$txtTransferID','$fromWarehouseID','$toWarehouseID','$txtTransferDate','$EmployeeID','$txtTotalQuantity','$txtTotalAmount','$cboStatus','$txtNote')";
        $ret1 = mysqli_query($connection, $Insert1);

        if($ret1) {
            // Insert Data to Dummy Table
            $size = count($_SESSION['Transfer_Functions']);
            $ret2 = true;

            for ($b = 0; $b < $size; $b++) { 
                $ProductID = $_SESSION['Transfer_Functions'][$b]['ProductID'];
                $TransferQuantity = $_SESSION['Transfer_Functions'][$b]['TransferQuantity'];
                $TransferPrice = $_SESSION['Transfer_Functions'][$b]['TransferPrice'];

                $Insert2 = "INSERT INTO `transferdetail`
                          (`TransferID`, `ProductID`, `Quantity`, `Price`) 
                          VALUES
                          ('$txtTransferID', '$ProductID', '$TransferQuantity','$TransferPrice')";
                $ret2 = $ret2 && mysqli_query($connection, $Insert2);
            }

            if($ret2) {
                unset($_SESSION['Transfer_Functions']);
                echo "<script>window.alert('Successfully Saved!')</script>";
                echo "<script>window.location='transferList.php'</script>";
              } else {
                echo "<p>Something went wrong in transferdetail insert: " . mysqli_error($connection) . "</p>";
            }
        } else {
            echo "<p>Something went wrong in sale insert: " . mysqli_error($connection) . "</p>";
        }
    }
}


if(isset($_GET['action'])) 
{
	$action=$_GET['action'];

	if($action === 'remove') 
	{
		$ProductID=$_GET['ProductID'];

		RemoveProduct($ProductID);
	}
	else if($action === 'clearall')
	{
		ClearAll();
	}
}

if(isset($_POST['btnAdd'])) 
{
	$ProductID=$_POST['cboProductID'];
	$TransferQuantity=$_POST['txtQuantity'];
  $TransferPrice=$_POST['txtTransferPrice'];

	AddProduct($ProductID,$TransferPrice,$TransferQuantity);
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

    <title>Aung Bi Win Rice Trading House - Create Transfer</title>
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
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
    />
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
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
              <a class="collapse-item" href="purchaseList.php">All Purchases</a>
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

        <li class="nav-item active">
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
              <h1 class="h3 mb-0 text-gray-800">Add Transfer</h1>
            </div>

            <!-- Content Row -->
            <div class="page-wrapper">
              <form method="POST" action="createTransfer.php">
                <div class="content">
                  <div class="page-header">
                    <div class="page-title">
                      <h6>Add/Update Transfer</h6>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="row mb-4">
                         <!-- TransferID -->
                         <div class="col-lg-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label for="SaleID">TransferID</label>
                            <input
                              type="text"
                              class="form-control"
                              id="txtTransferID"
                              name="txtTransferID"
                              value="<?php echo AutoID('transfer','TransferID','T-',4) ?>"
                              readonly
                            />
                          </div>
                        </div>

                        <!-- Transfer Date -->
                        <div class="col-lg-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label for="Transfer">Transfer Date</label>
                            <div class="input-group">
                              <input
                                type="text"
                                placeholder="DD-MM-YYYY"
                                class="form-control"
                                name="txtTransferDate"
                                value="<?php echo date('Y-m-d') ?>"
                              />
                              <div class="input-group-append">
                                <span class="input-group-text">
                                  <img src="img/calendars.svg" alt="calendar" />
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>

                          <!-- EmployeeID -->
                          <div class="col-lg-3 col-sm-6 col-12 d-none" >
                            <div class="form-group">
                                <label >Employee</label>
                                <div class="d-flex">
                                  <input class="form-control" type="text" name="txtEmployeeID" value="<?php echo $_SESSION['Username'] ?>" readonly >
                                </div>
                              </div>
                            </div>

                        <div class="row align-items-center">
                          <!-- Product Dropdown -->
                          <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label>Product</label>
                              <select class="form-control" name="cboProductID" id="cboProductID">
                                <option selected disabled>Choose Product</option>
                                <?php
                                $query = "SELECT * FROM product";
                                $ret = mysqli_query($connection, $query);
                                $count = mysqli_num_rows($ret);

                                for ($i = 0; $i < $count; $i++) {
                                  $arr = mysqli_fetch_array($ret);
                                  $ProductID = $arr['ProductID'];
                                  echo "<option value='$ProductID'>" . $arr['ProductName'] . "</option>";
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <!-- Quantity Input -->
                          <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label for="txtQuantity">Quantity (bags)</label>
                              <input class="form-control" type="number" name="txtQuantity" value="0" /> 
                            </div>
                          </div>

                          <!-- Transfer Price Input -->
                          <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label for="txtTransferPrice">Transfer Price</label>
                              <input class="form-control" type="number" name="txtTransferPrice" value="0" /> 
                            </div>
                          </div>

                          <!-- Buttons -->
                          <div class="col-lg-4 col-md-6 col-sm-12">
                            <input class="btn btn-primary me-2" type="submit" name="btnAdd" value="Add" />
                            <input class="btn btn-light" type="reset" name="btnClear" value="Clear" />
                          </div>
                        </div>   
                      </div>

                      <!-- Table Section -->
                      <div class="row">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Product Name</th>
                                <th>Transfer Price (per bag)</th>
                                <th>Qutnatity(bag)</th>
                                <th>In Stock</th>
                                <th class="text-end">Sub Total</th>

                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
  
                              if(!isset($_SESSION['Transfer_Functions'])) 
                              {
  
                              } else {                            
                                $size=count($_SESSION['Transfer_Functions']);
  
                                for($x=0;$x<$size;$x++) 
                                { 
                                  $ProductID=$_SESSION['Transfer_Functions'][$x]['ProductID'];
  
                                  echo "<tr>";
                                    echo "<td>"	. $_SESSION['Transfer_Functions'][$x]['ProductName'] ."</td>";
                                    echo "<td>"	. $_SESSION['Transfer_Functions'][$x]['TransferPrice'] ."</td>";
                                    echo "<td>"	. $_SESSION['Transfer_Functions'][$x]['TransferQuantity'] ."</td>";
                                    echo "<td>"	. $_SESSION['Transfer_Functions'][$x]['Quantity'] ."</td>";
                                    echo "<td>" . $_SESSION['Transfer_Functions'][$x]['TransferPrice'] * 
                                                  $_SESSION['Transfer_Functions'][$x]['TransferQuantity']. "</td>";
                                    echo "<td><a class='delete-set' href='createTransfer.php?action=remove&ProductID=$ProductID'><img src='img/delete.svg' alt='delete'/></a></td>";
                                  echo "</tr>";
                                }
                              }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>

                         <!-- Total Calculation Section -->
                      <div class="row">
                        <div class="col-lg-12 d-flex justify-content-end">
                          <div class="total-order">
                            <table class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                  <th class="text-center" colspan="2">Transfer Summary</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><strong>Total Quantity</strong></td>
                                  <td><input type="text" class="form-control-plaintext" name="txtTotalQuantity" value="<?php echo CalculateTotalQuantity(); ?>" readonly> bags</td>
                                </tr>
                                <tr>
                                  <td><strong>Total Amount</strong></td>
                                  <td><input type="text" class="form-control-plaintext" name="txtTotalAmount" value="<?php echo CalculateTotalAmount(); ?>" readonly> MMK</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <!-- Form Section -->
                      <div class="row">
                                                <!-- From WarehouseID -->
                                                <div class="col-lg-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label >From Warehouse*</label>
                            <div class="d-flex">
                              <select class="form-control" name="fromWarehouseID" >
                                <option value="" selected disabled>Choose Warehouse</option>
                                <?php
                                $query = "SELECT * FROM warehouse WHERE Status = 'Active'";
                                $ret = mysqli_query($connection, $query);
                                $count = mysqli_num_rows($ret);

                                for ($i = 0; $i < $count; $i++) {
                                $arr = mysqli_fetch_array($ret);
                                $WarehouseID = $arr['WarehouseID'];
                                echo "<option value='$WarehouseID'>" . $arr['WarehouseName'] . "</option>";
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <!-- To WarehouseID -->
                        <div class="col-lg-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label >To Warehouse*</label>
                            <div class="d-flex">
                              <select class="form-control" name="toWarehouseID" >
                                <option value="" selected disabled>Choose Warehouse</option>
                                <?php
                                $query = "SELECT * FROM warehouse WHERE Status = 'Active'";
                                $ret = mysqli_query($connection, $query);
                                $count = mysqli_num_rows($ret);

                                for ($i = 0; $i < $count; $i++) {
                                $arr = mysqli_fetch_array($ret);
                                $WarehouseID = $arr['WarehouseID'];
                                echo "<option value='$WarehouseID'>" . $arr['WarehouseName'] . "</option>";
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label>Status</label>
                            <select  class="form-control" id="cboStatus" name="cboStatus" >
                              <option value="" >Choose Status</option>
                              <option value="Completed" >Completed</option>
                              <option value="Send">Send</option>
                              <option value="Pending">Pending</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label>Note</label>
                            <textarea name="txtNote" class="form-control"></textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <input type="submit" name="btnSave" value="Submit" class="btn btn-primary" />
                          <a href="createTransfer.php?action=clearall" class="btn btn-light"
                            >Cancel</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>  
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <script>
      // Initialize Flatpickr on Purchase Date Input
      document.addEventListener("DOMContentLoaded", function () {
        flatpickr("#purchaseDate", {
          dateFormat: "d-m-Y", // Format for DD-MM-YYYY
          defaultDate: new Date(), // Optional: Set default date to today
          onChange: function (selectedDates, dateStr) {
            console.log("Selected Purchase Date:", dateStr); // Handle selected date
          },
        });
      });
    </script>
  </body>
</html>
