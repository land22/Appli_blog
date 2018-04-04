<?php ob_start(); ?>
<form>
  <div class="form-group">
    <label for="exampleInputEmail1">Titre</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="titre du post">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Contenu</label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Content Post">
  </div>
  <textarea>le contenu du post</textarea>
  <button type="submit" class="btn btn-primary">Valider</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'template_admin.php'; ?>