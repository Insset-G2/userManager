<?php
session_start();

require_once './includes/Database.php';

if (isset($_SESSION["user"]) && $_SESSION["connection"] !== null) {

  $database = new Database();

  $username = $database->select("SELECT login FROM user WHERE id = " . $_SESSION["user"]);
  $ip = $database->select("SELECT * FROM noippublic");
  $update = $database->select("SELECT * FROM countupdate");
  $infoServer = $database->select("SELECT id, cputmp, ramfree, ramused, diskused, disktotal, dateupdate FROM updateinfoserver");
} else {
  header("Location: https://apimaster.flixmail.fr/infoip-noip");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Dashboard NoIp custom</title>
  <!-- [Meta] -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="dashboard information NoIp" />
  <meta name="author" content="FILLEUX Dimitri" />
  <link rel="icon" type="image/x-icon" href="./pages/assets/images/favicon.ico">
  <!-- Favicon au format .png -->
  <link rel="icon" type="image/png" href="./pages/assets/images/favicon-32.png">
  <!-- [Google Font : Public Sans] icon -->
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="./pages/assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="./pages/assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="./pages/assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="./pages/assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="./pages/assets/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="./pages/assets/css/style-preset.css">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">

  <!-- [ Sidebar Menu ] start -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="https://apimaster.flixmail.fr/dashboard-noip" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="./pages/assets/images/logo.png" alt="logo image" class="logo-lg" />
          <span class="badge bg-brand-color-2 rounded-pill ms-2 theme-version">v1.0</span>
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item pc-caption">
            <label>Navigation</label>
          </li>
          <li class="pc-item">
            <a href="#" class="pc-link">
              <span class="pc-micon">
                <i class="ph-duotone ph-gauge"></i>
              </span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>
        </ul>

      </div>
      <div class="card pc-user-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <img src="./pages/assets/images/user/avatar.jpg" alt="user-image" class="user-avtar wid-45 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
              <h6 class="mb-0"><?php echo $username[0]['dashlogin']; ?></h6>
              <small>Administrator</small>
            </div>
            <div class="dropdown">
              <a href="#" class="btn btn-icon btn-link-secondary avtar arrow-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,20">
                <i class="ph-duotone ph-windows-logo"></i>
              </a>
              <div class="dropdown-menu">
                <ul>
                  <li><a class="pc-user-links" href="https://apimaster.flixmail.fr/logout">
                      <i class="ph-duotone ph-power"></i>
                      <span>Logout</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <!-- [ Sidebar Menu ] end -->

  <!-- [ Main Content ] start -->
  <section class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Information IP public</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- Both borders table start -->
        <div class="col-xl-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>IP public</h5>
            </div>
            <div class="card-body table-border-style">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Date</th>
                      <th>IP</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($ip as $innerArray) {
                      echo "<tr>";
                      foreach ($innerArray as $value) {
                        echo "<td>" . $value . "</td>";
                      }
                      echo "</tr>";
                  }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Nombres de mise à jours</h5>
            </div>
            <div class="card-body table-border-style">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Action</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach ($update as $innerArray) {
                      echo "<tr>";
                      foreach ($innerArray as $value) {
                        echo "<td>" . $value . "</td>";
                      }
                      echo "</tr>";
                  }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Information serveur</h5>
            </div>
            <div class="card-body table-border-style">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Température CPU °C</th>
                      <th>Ram disponible</th>
                      <th>Ram utilisé</th>
                      <th>Disque utilisé</th>
                      <th>Disque</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach ($infoServer as $innerArray) {
                      echo "<tr>";
                      foreach ($innerArray as $value) {
                        echo "<td>" . $value . "</td>";
                      }
                      echo "</tr>";
                  }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Both borders table end -->
      </div>
      <!-- Border-bottom color table end-->
    </div>
    <!-- [ Main Content ] end -->
    </div>
  </section>
  <!-- [ Main Content ] end -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm-6 my-1">
          <p class="m-0">Made with &#9829; by FilleuxStudio</p>
        </div>
      </div>
    </div>
  </footer>
  <!-- Required Js -->
  <script src="./pages/assets/js/plugins/popper.min.js"></script>
  <script src="./pages/assets/js/plugins/simplebar.min.js"></script>
  <script src="./pages/assets/js/plugins/bootstrap.min.js"></script>
  <script src="./pages/assets/js/fonts/custom-font.js"></script>
  <script src="./pages/assets/js/pcoded.js"></script>
  <script src="./pages/assets/js/plugins/feather.min.js"></script>

</body>

</html>