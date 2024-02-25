<?php require_once '../server/db.php'; if ( !isset($_SESSION['user_login']) ) { header('Location: ../'); } ?>

<div class="row ml-0 mb-3">
    
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <p class="card-subtitle text-secondary text-uppercase small">Appearance</p>
                <div class="row ml-0 my-3 form-group">
                  <div class="col-sm-7">
                    <label class="control-label text-secondary mb-0">Choose theme</label>
                  </div>
                  <div class="col-sm-5">
                    <form class="theme" aria-hidden="true">
                        <span>
                            <input name="colorScheme" value="light" id="darkscheme" type="radio">
                            <label for="darkscheme"><img src="./img/theme/light.png" style="width: 60px; border-radius: 5px;"></img></label>
                        </span>
                        <span>
                            <input name="colorScheme" value="dark" id="lightscheme" type="radio">
                            <label for="lightscheme"><img src="./img/theme/dark.png" style="width: 60px; border-radius: 5px;"></img></label>
                        </span>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-5">
        <div class="card">
            <div class="card-body">
                <p class="card-subtitle text-secondary text-uppercase small">Account</p>
                <form action="#" onsubmit="changePassword(); return false" id="changePasswordForm">
                  <div class="row ml-0 my-3 form-group">
                    <div class="col-sm-4">
                      <label class="control-label text-secondary mb-0">Change password</label>
                    </div>
                    <div class="col-sm-3">
                      <input class="form-control" type="password" name="currentPassword" placeholder="Current Password...">
                    </div>
                    <div class="col-sm-3">
                      <input class="form-control" type="password" name="newPassword" placeholder="New Password...">
                    </div>
                    <div class="col-sm-1">
                      <button type="submit" class="btn btn-primary btn-center ripple text-uppercase">Change</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    
</div>