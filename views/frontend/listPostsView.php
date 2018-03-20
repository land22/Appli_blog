<?php $title = 'Liste des Posts'; ?>

<?php ob_start(); ?>


<?php
if ( !empty($aListposts) ) {
	while ($comment = $comments->fetch())
	{
	?>
	    <p><strong><?= htmlspecialchars($comment['author']) ?></strong> le <?= $comment['comment_date_fr'] ?></p>
	    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
		<?php
	}
} else {
	?>
	<p>Aucun enregistrement trouvé</p>
	<?php
}
?>


<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
