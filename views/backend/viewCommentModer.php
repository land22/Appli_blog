
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
                  <td style="word-break: break-word;"><?= htmlspecialchars($data['comment']) ?></td>
                  <td><?= $data['comment_date_fr'] ?></td>
                  <td><a href="index.php?action=delComment&id=<?= $data['id_comment']?>"><i title="Supprimé le commentaire" class="fa fa-trash-o" style="font-size:28px"></i></a> <a href="index.php?action=adminRestorComment&id=<?= $data['id_comment']?>"><i title="Restauré le commentaire" class="fa fa-refresh" style="font-size:28px"></i></a></td>
                </tr>
    <?php 
    } // fin de la boucle while
    ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php $content = ob_get_clean(); ?>
<?php
require 'template_admin.php'; ?>