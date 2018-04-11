<?php ob_start(); ?>
<form action="index.php?action=upPost&id=<?=$data['id_post']?>" method="Post">
  <div class="form-group">
    <label for="exampleInputEmail1">Titre</label>
    <input name="titlePost" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?=$data['title_post']?>">
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Sous titre</label>
    <input name="subTitle" type="text" class="form-control" id="exampleInputPassword1" value="<?=
$data['sub_title']?>">
  </div>
  <div class="form-group">
  <label for="exampleInputPassword1">Contenu</label>
  <textarea name="contentPost" value="<?= $data['content_post']?>"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Modifier</button>
  <input type="hidden" name="id" value="<?=$data['id_post']?>">
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'template_admin.php'; ?>