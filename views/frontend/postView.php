<?php $title = htmlspecialchars($post['title_post']); 
?>
<header class="masthead" style="background-image: url('public/theme_front/img/post-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h4 style="word-break: break-word;"><?= htmlspecialchars($post['title_post']) ?></h4>
              
            </div>
          </div>
        </div>
      </div>
    </header>
    <?php $header = ob_get_clean(); ?>

<?php ob_start(); ?>

    <div class="row">
     	<div class="col-lg-8 col-md-10 mx-auto">
     		<h3 style="word-break: break-word;"><?= htmlspecialchars($post['title_post']) ?></h3>
     		<p style="word-break: break-word;"><?= $post['content_post'] ?></p>
     		<p class="post-meta">Posté le <?= $post['creation_date_fr'] ?></p>
     		<?php
      if ( isset($comments) )
      {
      ?>
        <h3>Liste des commentaires associés à ce post</h3>
        <hr>
        <?php
		    while ($comment = $comments->fetch(PDO::FETCH_ASSOC))
		    {
		    ?>
		  <div class="post-preview">
		    <p style="word-break: break-word;">
		      <?= nl2br(htmlspecialchars($comment['comment'])) ?>
        <a title="Signalé ce commentaire" href="index.php?action=signalComment&id=<?= $comment['id_comment'] ?>&id_post=<?= $post['id_post'] ?>"><i class="fa fa-exclamation-circle" style="font-size:30px"></i></a>
		    <p>
		    <p class="post-meta">Commenté par
          <a href="#"><?= nl2br(htmlspecialchars($comment['author_comment'])) ?></a> Le <?= nl2br(htmlspecialchars($comment['comment_date_fr'])) ?></p>
		  </div>
          <hr>
			<?php
		    } // fin while

	    } // fin if 

      else
      {
		    echo '<h3 class="post-title">Aucun commentaire enregistré pour ce poste pour l\'instant</h3>';
	    } // fin else
      ?>
<form name="sentMessage" id="contactForm" action="index.php?action=addComment&id=<?= $post['id_post'] ?>" method="post">
	<legend>Laisser un commentaire</legend>
            <div class="control-group">
              <!--<div class="form-group floating-label-form-group controls">-->
                <label>Votre nom </label>
                <input type="text" class="form-control" placeholder="Votre nom" id="name" name="author" required data-validation-required-message="Please enter your name.">
                <p class="help-block text-danger"></p>
              <!--</div>-->
            </div>
           
            <div class="control-group">
             <!-- <div class="form-group floating-label-form-group controls">-->
                <label>Votre commentaire</label>
                <textarea rows="5" class="form-control" placeholder="Votre commentaire" id="message" required data-validation-required-message="Votre commentaire." name="comment"></textarea>
                <p class="help-block text-danger"></p>
              <!--</div>-->
            </div>
            <br>
            <div id="success"></div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary"  >Soumettre</button>
            </div>
          </form>
          </div><!--close mx-auto div-->
        </div><!-- close row div-->
    <hr>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>