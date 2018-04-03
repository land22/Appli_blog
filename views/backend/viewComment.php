<?php ob_start(); ?>
 <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Data Table Example</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Auteur</th>
                  <th>Commentaires</th>
                  <th>Date creation</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Auteur</th>
                  <th>Commentaires</th>
                  <th>Date creation</th>
                  <th>Action</th>
                </tr>
              </tfoot>
              <tbody>
                    <?php
    while ($data = $listComments->fetch(PDO::FETCH_ASSOC))
    {

    ?>
                <tr>
                  <td><?= htmlspecialchars($data['author_comment']) ?></td>
                  <td><?= htmlspecialchars($data['comment']) ?></td>
                  <td><?= htmlspecialchars($data['comment_date_fr']) ?></td>
                  <td><a><i class="fa fa-trash-o" style="font-size:28px"></i></a></td>
                </tr>
     <?php
       }
         ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <?php $content = ob_get_clean(); ?>

<?php require 'template_admin.php'; ?>