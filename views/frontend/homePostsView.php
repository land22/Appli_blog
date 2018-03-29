<?php $title = 'Home List Posts'; 
?>

<?php ob_start(); ?>



     
     <div class="row">
     	<?php

	if ( !empty($homePosts) ) {

		while ($listpost = $homePosts->fetch(PDO::FETCH_ASSOC))
		{

		?><div class="col-lg-8 col-md-10 mx-auto">
          <div class="post-preview">
		    <h2 class="post-title"><?= htmlspecialchars($listpost['title_post']) ?></h2><p class="post-meta">Posté le <?= $listpost['creation_date_fr'] ?></p>
		    <p class="post-subtitle"><?= nl2br(htmlspecialchars($listpost['sub_title'])) ?></p>
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
