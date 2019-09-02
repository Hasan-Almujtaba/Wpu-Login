            <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


          <div class="row">
          	<div class="col-lg-10">

          		<?= validation_errors(); ?>
          		<?= $this->session->flashdata('message'); ?>

          		<!-- Button Trigger Modal -->
          		
          		<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubMenu">Add New SubMenu</a>

				<table class="table table-hover">
				  <thead>
				    <tr>
				      <th scope="col">#</th>
				      <th scope="col">Title</th>
				      <th scope="col">Menu</th>
				      <th scope="col">Url</th>
				      <th scope="col">Icon</th>
				      <th scope="col">Active</th>
				      <th scope="col">Action</th>
				    </tr>
				  </thead>
				  <tbody>
					
					<?php $i = 1; ?>
				  	<?php foreach( $subMenu as $sm ) : ?>
				    <tr>
				      <th scope="row"><?= $i; ?></th>
				      <td><?= $sm['title']; ?></td>
				      <td><?= $sm['menu']; ?></td>
				      <td><?= $sm['url']; ?></td>
				      <td><?= $sm['icon']; ?></td>
				      <td><?= $sm['is_active']; ?></td>
				      <td>
				      	<a href="" class="badge badge-pill badge-primary">Edit</a> |
				      	<a href="" class="badge badge-pill badge-danger">Delete</a>
				      </td>
				    </tr>
				    <?php $i++; ?>
					<?php endforeach ?>

				  </tbody>
				</table>

          	</div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Modal -->
      <div class="modal fade" id="newSubMenu" tabindex="-1" role="dialog" aria-labelledby="newSubMenuLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="newSubMenuLabel">Add New SubMenu</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" action="<?= base_url('menu/submenu'); ?>" method="post">
	            <div class="modal-body">
	            	<div class="form-group">
	            	    <input type="text" class="form-control" id="menu" placeholder="Sub Menu Title" name="title">
	            	</div>
	            	<div class="form-group">
	            	  	<select name="menu_id" id="menu_id" class="form-control">
	            	  		<option value="">Select Menu</option>
	            	  		<?php foreach( $menu as $m ) : ?>
	            	  		<option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
	            	  		<?php endforeach ?>
	            	  	</select>
	            	</div>
	            	<div class="form-group">
	            	      <input type="text" class="form-control" id="url" placeholder="Sub Menu url" name="url">
	            	</div>
	            	<div class="form-group">
	            	      <input type="text" class="form-control" id="icon" placeholder="Sub Menu icon " name="icon">
	            	</div>
	            	<div class="form-group">
	            		<div class="form-check">
	            		  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
	            		  <label class="form-check-label" for="is_active">
	            		    Active
	            		  </label>
	            		</div>
	            	</div>
	            </div>

	            <div class="modal-footer">
	              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	              <button type="submit" class="btn btn-primary">Add</button>
	            </div>
            </form>
          </div>
        </div>
      </div>

