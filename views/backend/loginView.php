<?php $title = 'Admin blog' ?>
<?php ob_start(); ?>
<div class="card card-login mx-auto mt-5">
      <div class="card-header">Vos identifiants de connection</div>
      <div class="card-body">
        <?php 
         if (isset($_SESSION['error'])) {
           echo '<p style="color:#ea4335">'.$_SESSION['error'].'<p>';
         }

        ?>
        <form action="index.php?action=connect" method="POST">
          <div class="form-group">
            <label for="exampleInputPassword1">User name</label>
            <input class="form-control" id="exampleInputPassword1" type="text" name="username" placeholder="username">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input class="form-control" id="exampleInputPassword1" name="password" type="password" placeholder="Password">
          </div>
          <button class="btn btn-primary btn-block" type="">Valider</button>
        </form>
      </div>
    </div>
  <?php 
  $content = ob_get_clean();
   require 'template_login.php';

  ?>