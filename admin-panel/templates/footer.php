<!-- Core Scripts  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ionic/1.3.2/js/ionic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mapael/2.2.0/js/jquery.mapael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mapael/2.2.0/js/maps/world_countries.js"></script>
<script>
    var refreshTime = 600000; // every 10 minutes
    window.setInterval( function() {
        $.ajax({
            cache: false,
            type: "GET",
            url: './server/refreshSession.php',
            success: function(data) {
            }
        });
    }, refreshTime );
</script>

<!-- Custom JS -->
<script src="./js/main.js"></script>