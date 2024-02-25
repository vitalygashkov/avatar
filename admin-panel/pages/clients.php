<?php require_once '../server/db.php'; if ( !isset($_SESSION['user_login']) ) { header('Location: ../'); } ?>

<!--<h2 class="font-black mb-3">Clients</h2>-->
<!--<hr>-->

<div class="mb-3">
    <div class="card">
        <div class="card-body">
            <p class="card-subtitle text-secondary text-uppercase small">Actions</p>
            <button type="button" class="btn btn-primary ripple mr-2 text-uppercase" onclick="deleteDead();">Delete Dead</button>
            <button class="btn btn-default btn-circle ripple"><i class="far fa-ellipsis-h fa-lg"></i></button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="card-subtitle text-secondary text-uppercase small">Client List</p>
        <table id="clientsTable" class="display table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead class="text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Country</th>
                    <th>Operating System</th>
                    <th>Role</th>
                    <th>Antivirus</th>
                    <th>RAM</th>
                    <th>Storage</th>
                    <th>Network</th>
                    <th>Added</th>
                    <th>Status</th>
                    <th>Version</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>