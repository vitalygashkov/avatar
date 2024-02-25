<?php if ( !isset($_SESSION['user_login']) ) header('Location: ../templates/401.php'); ?>
<main role="main" class="main">
	<div class="tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="nav-dashboard" role="tabpanel" aria-labelledby="nav-dashboard-tab"></div>
		<div class="tab-pane fade" id="nav-clients" role="tabpanel" aria-labelledby="nav-clients-tab"></div>
		<div class="tab-pane fade" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab"></div>
		<div class="tab-pane fade" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab"></div>
        <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab"></div>
		<div class="tab-pane fade" id="nav-logout" role="tabpanel" aria-labelledby="nav-logout-tab"></div>
	</div>
</main>