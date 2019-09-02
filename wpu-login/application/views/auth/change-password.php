  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-lg-7">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              
              <div class="col-lg">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 ">Change Your Password? for</h1>
                    <h5 class="mb-4"><?= $this->session->userdata('reset_email'); ?></h5>
                  </div>

                  <form class="user" method="post" action="<?= base_url('auth/changepassword'); ?>">
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Enter New Password..">
                      <small id="password" class="form-text text-danger pl-2"><?= form_error('password'); ?></small>
                    </div>

                    <div class="form-group">
                      <input type="password" name="confirmPassword" class="form-control form-control-user" id="confirmPassword" placeholder="Confirm New Password..">
                      <small id="confirmPassword" class="form-text text-danger pl-2"><?= form_error('confirmPassword'); ?></small>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                      Change Password
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

