<?php $title = 'Liste des Posts'; 
?>

<?php ob_start(); ?>



     
     <div class="row">
     	<?php

	if ( !empty($listPosts) ) {

		while ($listpost = $listPosts->fetch(PDO::FETCH_ASSOC))
		{

		?><div class="col-lg-8 col-md-10 mx-auto">
          <div class="post-preview">
		    <h2 class="post-title"><?= htmlspecialchars($listpost['title_post']) ?></h2><p class="post-meta">Posté le <?= $listpost['date_post'] ?></p>
		    <p class="post-subtitle"><?= nl2br(htmlspecialchars($listpost['content_post'])) ?></p>
		</div>
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


<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
