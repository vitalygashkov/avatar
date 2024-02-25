<?php require_once '../server/db.php'; if ( !isset($_SESSION['user_login']) ) { header('Location: ../'); } ?>

<div class="mb-3">
    <div class="card">
        <div class="card-body">
            <p class="card-subtitle text-secondary text-uppercase small">Actions</p>
            <button type="button" class="btn btn-primary ripple mr-2 text-uppercase" data-toggle="modal" data-target=".createUser-modal">New User</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="card-subtitle text-secondary text-uppercase small">Users</p>
        <table id="usersTable" class="display table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead class="text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>Role</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade createUser-modal" tabindex="-1" role="dialog" aria-labelledby="createUser" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form action="#" onsubmit="createUser(); return false" id="createUserForm">
            <div class="modal-header">
                <h5 class="modal-title font-black">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
            </div>
            <div class="modal-body pr-5">

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Username:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="username" required>
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Password:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="password" name="password" required>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Role:</label>
                    </div>
                    <div class="col-sm-8">
                        <select class="custom-select form-control" name="role" placeholder="Select">
                        <option>Administrator</option>
                        <option>User</option>
                        <option>Guest</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-center ripple text-uppercase">Create</button>
            </div>
        </form>
    </div>
  </div>
</div>