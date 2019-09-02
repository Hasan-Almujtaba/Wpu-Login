            <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

          <div class="row">
          	<div class="col-lg-6">

          		<?= $this->session->flashdata('message'); ?>
          		
          		<form action="<?= base_url('user/changepassword'); ?>" method="post">

          			<div class="form-group">
          			    <label for="currentPassword">Current Password</label>
          			    <input type="password" class="form-control" id="currentPassword" name="currentPassword">
          			</div>
          			<small id="email" class="form-text text-danger pl-2"><?= form_error('currentPassword'); ?></small>

          			<div class="form-group">
          			    <label for="newPassword">New Password</label>
          			    <input type="password" class="form-control" id="newPassword" name="newPassword">
          			</div>
          			<small id="email" class="form-text text-danger pl-2"><?= form_error('newPassword'); ?></small>

          			<div class="form-group">
          			    <label for="confirmNewPassword">Confirm New Password</label>
          			    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword">
          			</div>
          			<small id="email" class="form-text text-danger pl-2"><?= form_error('confirmNewPassword'); ?></small>

          			<div class="form-group">
          			    <button class="btn btn-primary" type="submit">Change Password</button>
          			</div>
          			
          		</form>

          	</div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

