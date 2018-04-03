
<?php ob_start(); ?>
 <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Liste des posts du site</div>
        <div class="card-body">
         <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Titre du post</th>
                  <th>Sous titre</th>
                  <th>Date creation</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Titre du post</th>
                  <th>Sous titre</th>
                  <th>Date creation</th>
                  <th>Actions</th>
                </tr>
              </tfoot>
              <tbody>
              	<?php
		while ($data = $listPosts->fetch(PDO::FETCH_ASSOC))
		{

		?>
                <tr>
                  <td><?= htmlspecialchars($data['title_post']) ?></td>
                  <td><?= htmlspecialchars($data['sub_title']) ?></td>
                  <td><?= $data['creation_date_fr'] ?></td>
                  <td><a><i style="font-size:28px" class="fa fa-edit"></i></a> <a><i class="fa fa-trash-o" style="font-size:28px"></i></a></td>
                </tr>

                <?php }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
    

    <?php $content = ob_get_clean(); ?>
<?php
require 'template_admin.php'; ?>