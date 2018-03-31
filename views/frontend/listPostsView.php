<?php $title = 'Liste des Posts du site'; 
?>
<header class="masthead" style="background-image: url('public/theme_front/img/post-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h4>liste des posts du site</h4>
              
            </div>
          </div>
        </div>
      </div>
    </header>
    <?php $header = ob_get_clean(); ?>

<?php ob_start(); ?>



     
     <div class="row">
     	<div class="col-lg-8 col-md-10 mx-auto">
     	<?php

	if ( !empty($listPosts) ) {

		while ($listpost = $listPosts->fetch(PDO::FETCH_ASSOC))
		{

		?>
		<div class="post-preview">
		    <a href="index.php?action=post&id=<?= $listpost['id_post']?>">
              <h2 class="post-title">
		    <?= htmlspecialchars($listpost['title_post']) ?>
		    </h2>
		    <h3 class="post-subtitle">
		    <?= nl2br(htmlspecialchars($listpost['sub_title'])) ?>
		    </h3>
            </a>
		    <p class="post-meta">Posté le <?= $listpost['creation_date_fr'] ?></p>
		</div>
          <hr>
			<?php
		}

	} 

	  else {
	  	
		?>
		<p>Aucun enregistrement trouvé</p>
		<?php
	}
?>
          </div><!--close mx-auto div-->
        </div><!-- close row div-->
      

    <hr>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>