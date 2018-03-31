<?php $title = 'Home List Posts'; 
?>
<?php ob_start(); ?>
<header class="masthead" style="background-image: url('public/theme_front/img/home-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1>Blog pour ecrivain</h1>
              <span class="subheading">Un blog Permettant de publier un livre en chapitre tout en permettant de poster les commentaires sur chaque chapitre</span>
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

	if ( !empty($homePosts) ) {

		while ($listpost = $homePosts->fetch(PDO::FETCH_ASSOC))
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
		<p class="post-meta">Aucun enregistrement trouvé</p>
		<?php
	}
?>

          <!-- Pager -->
          <div class="clearfix">
            <a class="btn btn-primary float-right" href="index.php?action=<?php echo "listPosts"; ?>">Autres posts &rarr;</a>
          </div>
        </div>
        </div>
      

    <hr>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
