<!--Header-->
<?php include "../sv_header.html"; ?>


<!--NAV BAR-->
<?php include "../sv_nav.html"; ?>


<script>
    $('document').ready(function() {
        $('#01_btn').click(function() {
            $.get('http://node.cams.schulzvideo.com/get_recent_games', function(data, status) {
                var result = JSON.parse(data);

                // build dropdown 
                var built_html = `<table class='table'><thead><tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
                    <th>Sport</th></tr></thead><tbody>`;
                for(var i=0; i<result.length; i++) {
                    var d = new Date(result[i]['date']);
                    built_html += '<tr><td>' + result[i]['id'] + '</td><td>';
                    built_html += d.toDateString() + '</td><td>';
                    built_html += result[i]['t1name'] + '</td><td>';
                    built_html += result[i]['t2name'] + '</td><td>';
                    built_html += result[i]['sport'] + '</td></tr>';
                }
                built_html += '</tbody></table>'

                // apply to dropdown on page
                $('#01_footer').html(built_html);
            });
        });

        $('#02_btn').click(function(e) {
            e.preventDefault();
            var form_value = $('#02_name').val();
            $.get('http://node.cams.schulzvideo.com/get_teams?name='+form_value, function(data, status) {
                var result = JSON.parse(data);

                // build dropdown 
                var built_html = `<table class='table'><thead><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Sport</th></tr></thead><tbody>`;
                for(var i=0; i<result.length; i++) {
                    var d = new Date(result[i]['date']);
                    built_html += '<tr><td>' + result[i]['id'] + '</td><td>';
                    built_html += result[i]['name'] + '</td><td>';
                    built_html += result[i]['sport'] + '</td></tr>';
                }
                built_html += '</tbody></table>'

                // apply to dropdown on page
                $('#02_footer').html(built_html);
            });
        });
    });

</script>


<!--BODY-->
<body>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">

            <br>
            <h1 class='text-center'>Admin Page</h1>

            <br>
            <br>

                
            <div class='card'>
                <div class='card-header'>
                    <h4>01 Show Games</h4>
                </div>
                <div class='card-body'>
                    <button class='btn' id='01_btn'>Display</button>
                </div>
                <div class='card-footer' id='01_footer'>
                </div>
            </div>

            <div class='card'>
                <div class='card-header'>
                    <h4>02 Search Teams</h4>
                </div>
                <div class='card-body'>
                    <form>
                        <label>Team Name:</label>
                        <input type='text' id='02_name'>
                        <button class='btn' id='02_btn'>Search</button>
                    </form>
                </div>
                <div class='card-footer' id='02_footer'></div>
            </div>
            
            

            
                

        </div>
        <div class="col-lg-2"></div>
    </div>

</body>

</html>