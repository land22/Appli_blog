
<?php ob_start(); ?>

 <div class="card mb-3">

        <div class="card-header">
          <i class="fa fa-table"></i> Liste des commentaires signalés</div>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Commentataire</th>
                  <th class="cel_comment">Commentaire</th>
                  <th>Date du commentaire</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Commentataire</th>
                  <th class="cel_comment">Commentaire</th>
                  <th>Date du commentaire</th>
                  <th>Actions</th>
                </tr>
              </tfoot>
              <tbody>
              	<?php
		while ($data = $listComments->fetch(PDO::FETCH_ASSOC))
		{

		?>
                <tr>
                  <td><?= htmlspecialchars($data['author_comment']) ?></td>
                  <td ><?= htmlspecialchars($data['comment']) ?></td>
                  <td><?= $data['comment_date_fr'] ?></td>
                  <td><a class="btn btn-primary" href="index.php?action=adminListComment&id=<?= $data['id_comment']?>">Moderé</a> <a class="btn btn-primary" href="index.php?action=formUpdatePost&id=<?= $data['id_comment']?>">Restauré</a></td>
                </tr>

                <?php } // fin de la boucle while
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
    

    <?php $content = ob_get_clean(); ?>
<?php
require 'template_admin.php'; ?>