<?php require_once '../server/db.php'; if ( !isset($_SESSION['user_login']) ) { header('Location: ../'); } ?>

<div class="mb-3">
    <div class="card">
        <div class="card-body">
            <p class="card-subtitle text-secondary text-uppercase small">Actions</p>
            <button type="button" class="btn btn-primary ripple mr-2 text-uppercase" data-toggle="modal" data-target=".createTask-modal">New Task</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="card-subtitle text-secondary text-uppercase small">Task List</p>
        <table id="tasksTable" class="display table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead class="text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Content</th>
                    <th>Target</th>
                    <th>Trigger</th>
                    <th>Total Done</th>
                    <th>Total Failed</th>
                    <th>Added</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade editTask-modal" tabindex="-1" role="dialog" aria-labelledby="editTask" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form action="#" onsubmit="editTask(); return false" id="editTaskForm">
            <div class="modal-header">
                <h5 class="modal-title font-black">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
            </div>
            <div class="modal-body pr-5">
                
                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Task ID:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="editTaskID" value="" disabled>
                        <input type="hidden" name="editTaskID" value="">
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Name:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="editTitle" value="" required>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Type:</label>
                    </div>
                    <div class="col-sm-8">
                        <select class="custom-select form-control" name="editType" placeholder="Select">
                            <option value="Download & Execute">Download & Execute</option>
                            <option value="Execute">Execute</option>
                            <option value="Terminate">Terminate</option>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Content:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="editContent" value="">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Target:</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="custom-control custom-radio custom-control-inline" data-toggle="collapse" data-target="#editCollapseCustomTargets" aria-expanded="false" aria-controls="editCollapseCustomTargets">
                            <input type="radio" id="editTargetRadio1" name="editTarget" class="custom-control-input" value="All" checked>
                            <label class="custom-control-label" for="editTargetRadio1">All</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline" id="editTustomTargets" data-toggle="collapse" data-target="#editCollapseCustomTargets" aria-expanded="false" aria-controls="editCollapseCustomTargets">
                            <input type="radio" id="editTargetRadio2" name="editTarget" class="custom-control-input" value="Custom">
                            <label class="custom-control-label" for="editTargetRadio2">Custom</label>
                        </div>
                    </div>
                </div>

                <div id="editCollapseCustomTargets" class="collapse" aria-labelledby="customTargets" data-parent=".modal-body">

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Limit:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="editLimit" placeholder="1000">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Client ID:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="editClient_id" placeholder="13, 45, 63">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Location:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="editLocation" placeholder="US, BR, RU">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">OS:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="editOs" placeholder="Windows 10">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Antivirus:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="editAntivirus" placeholder="Kaspersky Internet Security">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Storage:</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="custom-select form-control" name="editStorage_type" placeholder="Select">
                                        <option value="Include">Include</option>
                                        <option value="Exclude">Exclude</option>
                                    </select>
                                </div>
                                <input class="form-control" type="text" name="editStorage" placeholder="500-2000">
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Network:</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="custom-select form-control" name="editNetwork_type" placeholder="Select">
                                        <option value="Include">Include</option>
                                        <option value="Exclude">Exclude</option>
                                    </select>
                                </div>
                                <input class="form-control" type="text" name="editNetwork" placeholder="5-10">
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Role:</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="custom-select form-control" name="editRole" placeholder="Select">
                                <option value="Any">Any</option>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Trigger:</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="editTriggerRadio1" name="editTrigger" class="custom-control-input" value="Every Client" checked>
                            <label class="custom-control-label" for="editTriggerRadio1">Every Client</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="editTriggerRadio2" name="editTrigger" class="custom-control-input" value="On Connect">
                            <label class="custom-control-label" for="editTriggerRadio2">On Connect</label>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Initial State:</label>
                    </div>
                    <div class="col-sm-8">
                        <select class="custom-select form-control" name="editStatus" placeholder="Select">
                            <option value="Active">Active</option>
                            <option value="Paused">Paused</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-center ripple text-uppercase">Edit</button>
            </div>
        </form>
    </div>
  </div>
</div>