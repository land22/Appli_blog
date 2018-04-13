<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Administration du blog</title>
  <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script>
  <!-- Bootstrap core CSS-->
  <link href="public/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="public/admin/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="public/admin/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="public/admin/css/sb-admin.css" rel="stylesheet">

  <link href="public/admin/css/style.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.html">Administration du blog</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Liste des Posts">
          <a class="nav-link" href="index.php">
            <span class="nav-link-text">Retour au blog</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Liste des Posts">
          <a class="nav-link" href="index.php?action=adminListPost">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Liste des Posts</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Liste des commentaires">
          <a class="nav-link "  href="index.php?action=adminListCommentModer" >
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text">Liste des commentaires signalés</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <!-- Example DataTables Card-->
     <?= $content ?>
     </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © landry_developpement 2018</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Quitter ?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Selectionner "Deconnexion" ci-dessus si vous voulez fermer votre session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
            <a class="btn btn-primary" href="index.php?action=disconnect">Déconnexion</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="public/admin/vendor/jquery/jquery.min.js"></script>
    <script src="public/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="public/admin/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="public/admin/vendor/datatables/jquery.dataTables.js"></script>
    <script src="public/admin/vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="public/admin/js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="public/admin/js/sb-admin-datatables.min.js"></script>

    
  </div>
</body>

</html>
