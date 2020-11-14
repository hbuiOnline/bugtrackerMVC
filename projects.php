<?php
  session_start();
  include 'includes/autoloader.inc.php';
  $projectObj = new ProjectsView();
  $userObj = new UsersView();
 ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Bug Tracker - Projects</title>

  <!-- Custom fonts for this template -->
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      include 'sidebar.php';
     ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include 'topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Projects</h1>

          <?php
            if ($_SESSION['userID'] && $rbac->Users->hasRole('admin', $_SESSION['userID'])) {
              ?>
              <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#projectModal">
                <span class="icon text-white-50">
                  <i class="fas fa-project-diagram"></i>
                </span>
                <span class="text">Add Project</span>
              </a>

              <a href="#" class="btn btn-info btn-icon-split" data-toggle="modal" data-target="#projectAssignModal">
                <span class="icon text-white-50">
                  <i class="fas fa-user-tag"></i>
                </span>
                <span class="text">Project Assign</span>
              </a>
              <?php
            }
           ?>


          <?php
            if(isset($_GET['projectcreated'])){
               if ($_GET['projectcreated'] == 'success') {
                 echo '<p class="alert alert-success mt-3 mx-auto text-center" style="width: 300px" role="alert"> Project has been created!</p>';
               }
             }
             elseif (isset($_GET['projectassign'])) {
               if ($_GET['projectassign'] == 'success') {
                 echo '<p class="alert alert-success mt-3 mx-auto text-center" style="width: 300px" role="alert"> Project has been Assigned!</p>';
               }
             }
             elseif (isset($_GET['error'])) {
               if ($_GET['error'] == 'emptyfields') {
                 echo '<p class="alert alert-danger mt-3 mx-auto text-center" style="width: 300px" role="alert"> Empty fields !!</p>';
               }
               elseif ($_GET['error'] == 'sqlerror') {
                 echo '<p class="alert alert-danger mt-3 mx-auto text-center" style="width: 300px" role="alert"> SQL Error</p>';
               }
               elseif ($_GET['error'] == 'notprojectmanager') {
                  echo '<p class="alert alert-danger mt-3 mx-auto text-center" style="width: 300px" role="alert"> User does not have Project Manager Role.</p>';
               }
             }
           ?>
          <hr class="divider">

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Project Datatable</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered display" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Created</th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Assigned</th>
                      <th>Details</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Created</th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Assigned</th>
                      <th>Details</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                      // echo $projectObj->projectDataShow();
                      $projectArray = $projectObj->projectDataShow($_SESSION['userID']);
                      if ($_SESSION['userID'] && $rbac->Users->hasRole('admin', $_SESSION['userID'])) {
                        echo $projectArray['projectalldata'];
                      }
                      elseif($_SESSION['userID'] && $rbac->Users->hasRole('project-manager', $_SESSION['userID'])){
                        echo $projectArray['projectmanagerdata'];
                      }
                     ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span> Copyright &copy; <script>document.write(new Date().getFullYear());</script> Bug Tracker MVC. All rights reserved.</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Project Modal -->
  <div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Project Creation</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <form class="user" action="includes/projectmanager.inc.php" method="POST">

          <div class="form-group">
            <input type="text" class="form-control ticket-mordal" name="projecttitle" placeholder="Project Title">
          </div>
          <div class="form-group">
            <input type="text" class="form-control ticket-mordal" name="projectdescription" placeholder="Project Description">
          </div>

          <label class="optionlist" id="dropdown-options" for="assignto">
            <svg class="bi bi-card-heading" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
             <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h13a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5zm-13-1A1.5 1.5 0 000 3.5v9A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0014.5 2h-13z" clip-rule="evenodd"/>
             <path fill-rule="evenodd" d="M3 8.5a.5.5 0 01.5-.5h9a.5.5 0 010 1h-9a.5.5 0 01-.5-.5zm0 2a.5.5 0 01.5-.5h6a.5.5 0 010 1h-6a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
             <path d="M3 5.5a.5.5 0 01.5-.5h9a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5v-1z"/>
           </svg>
            User Assign: </label>
          <select multiple class="form-control ticket-option" name="assignto">
            <option value="<?php echo NULL; ?>">---</option>
            <?php
               echo $userObj->usersNameShow();
             ?>
          </select>

          <button class="btn btn-primary btn-mordal" type="submit" name="project-create">Create</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Project Assign Modal -->
  <div class="modal fade" id="projectAssignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Project Assign</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <form class="user" action="includes/projectmanager.inc.php" method="POST">

          <label class="optionlist" id="dropdown-options" for="projectname">
            <svg class="bi bi-card-heading" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
             <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h13a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5zm-13-1A1.5 1.5 0 000 3.5v9A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0014.5 2h-13z" clip-rule="evenodd"/>
             <path fill-rule="evenodd" d="M3 8.5a.5.5 0 01.5-.5h9a.5.5 0 010 1h-9a.5.5 0 01-.5-.5zm0 2a.5.5 0 01.5-.5h6a.5.5 0 010 1h-6a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
             <path d="M3 5.5a.5.5 0 01.5-.5h9a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5v-1z"/>
           </svg>
            Project Name: </label>
          <select class="form-control ticket-option" name="projectid">
            <?php
               echo $projectObj->projectDataOptionsInput();
             ?>
          </select>

          <label class="optionlist" id="dropdown-options" for="userassign">
            <svg class="bi bi-card-heading" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
             <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h13a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5zm-13-1A1.5 1.5 0 000 3.5v9A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0014.5 2h-13z" clip-rule="evenodd"/>
             <path fill-rule="evenodd" d="M3 8.5a.5.5 0 01.5-.5h9a.5.5 0 010 1h-9a.5.5 0 01-.5-.5zm0 2a.5.5 0 01.5-.5h6a.5.5 0 010 1h-6a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
             <path d="M3 5.5a.5.5 0 01.5-.5h9a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5v-1z"/>
           </svg>
            User Assign: </label>
          <select multiple class="form-control ticket-option" name="userassign">
            <option value="<?php echo NULL; ?>">---</option>
            <?php
               echo $userObj->usersNameShow();
             ?>
          </select>

          <button class="btn btn-primary btn-mordal" type="submit" name="project-assign">Assign</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="includes/logout.inc.php">Logout</a>
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
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="style/datatables.js"></script>

</body>

</html>
