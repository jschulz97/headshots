<!--Header-->
<?php include "../sv_header.html"; ?>


<!--NAV BAR-->
<?php include "../sv_nav.html"; ?>


<script>
    // do one function with api call and table input to list everything in results
    // api needs to return table column names?

    $('document').ready(function() {
        $('#01_btn').click(function(e) {
            e.preventDefault();
            populate_table('http://node.cams.schulzvideo.com/get_recent_games', $('#01'),
                ['ID','Date','Team 1','Team 2','Sport'],
                ['id','date','t1name','t2name','sport']);
            // $.get('http://node.cams.schulzvideo.com/get_recent_games', function(data, status) {
            //     var result = JSON.parse(data);

            //     // build dropdown 
            //     var built_html = `<table class='table'><thead><tr>
            //         <th>ID</th>
            //         <th>Date</th>
            //         <th>Team 1</th>
            //         <th>Team 2</th>
            //         <th>Sport</th></tr></thead><tbody>`;
            //     for(var i=0; i<result.length; i++) {
            //         var d = new Date(result[i]['date']);
            //         built_html += '<tr><td>' + result[i]['id'] + '</td><td>';
            //         built_html += d.toDateString() + '</td><td>';
            //         built_html += result[i]['t1name'] + '</td><td>';
            //         built_html += result[i]['t2name'] + '</td><td>';
            //         built_html += result[i]['sport'] + '</td></tr>';
            //     }
            //     built_html += '</tbody></table>'

            //     // apply to dropdown on page
            //     $('#01_footer').html(built_html);
            // });
        });

        $('#02_btn').click(function(e) {
            e.preventDefault();
            var form_value = $('#02_name').val();
            populate_table('http://node.cams.schulzvideo.com/get_teams?name='+form_value, $('#02'),
                ['ID','Name','Sport'],
                ['id','name','sport']);
            
            // var form_value = $('#02_name').val();
            // $.get('http://node.cams.schulzvideo.com/get_teams?name='+form_value, function(data, status) {
            //     var result = JSON.parse(data);

            //     // build dropdown 
            //     var built_html = `<table class='table'><thead><tr>
            //         <th>ID</th>
            //         <th>Name</th>
            //         <th>Sport</th></tr></thead><tbody>`;
            //     for(var i=0; i<result.length; i++) {
            //         var d = new Date(result[i]['date']);
            //         built_html += '<tr><td>' + result[i]['id'] + '</td><td>';
            //         built_html += result[i]['name'] + '</td><td>';
            //         built_html += result[i]['sport'] + '</td></tr>';
            //     }
            //     built_html += '</tbody></table>'

            //     // apply to dropdown on page
            //     $('#02_footer').html(built_html);
            // });
        });


        function populate_table(uri, parent_div, col_labels, col_names) {
            $.get(uri, function(data, status) {
                var result = JSON.parse(data);

                // build dropdown 
                var built_html = `<table class='table'><thead><tr>`;
                for(var i=0; i<col_labels.length; i++) {
                    built_html += '<th>' + col_labels[i] + '</th>';
                }
                built_html += `</tr></thead><tbody>`;
                for(var i=0; i<result.length; i++) {
                    built_html += '<tr>'
                    for(var key in col_names) {
                        var val = 'none';
                        if(col_names[key] == 'date') {
                            val = new Date(result[i]['date']).toDateString();
                        } else {
                            val = result[i][col_names[key]];
                        }
                        built_html += '<td>' + val + '</td>';
                    }
                    built_html += '</tr>'
                }
                built_html += '</tbody></table>'

                // apply to dropdown on page
                parent_div.children('.card-footer').html(built_html);
            });
        }


        $('#03_btn').click(function(e) {
            e.preventDefault();
            var team_name = $('#03_name').val();
            var team_sport = $('#03_sport').val();
            populate_table('http://node.cams.schulzvideo.com/add_team?name='+team_name+'&sport='+team_sport, $('#03'),
                ['ID','Name','Sport'],
                ['id','name','sport']);
        });


        $('#04_btn').click(function(e) {
            e.preventDefault();
            var team_id = $('#04_teamid').val();
            $.get('http://node.cams.schulzvideo.com/execute_scrape?team_id='+team_id, function(data, status) {
                
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

                
            <div class='card' id='01'>
                <div class='card-header'>
                    <h4>01 Show Games</h4>
                </div>
                <div class='card-body'>
                    <button class='btn' id='01_btn'>Display</button>
                </div>
                <div class='card-footer'>
                </div>
            </div>

            <div class='card' id='02'>
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
                <div class='card-footer'></div>
            </div>

            <div class='card' id='03'>
                <div class='card-header'>
                    <h4>03 Add Team</h4>
                </div>
                <div class='card-body'>
                    <form>
                        <label>Team Name:</label>
                        <input type='text' id='03_name'>
                        <label>Sport ID:</label>
                        <input type='text' id='03_sport'>
                        <button class='btn' id='03_btn'>Add Team</button>
                    </form>
                    <form method='get' action='http://node.cams.schulzvideo.com/admin/upload_roster'>
                        <button class='btn' type='submit'>Upload Roster</button>
                    </form>
                </div>
                <div class='card-footer'></div>
            </div>
            
            <div class='card' id='04'>
                <div class='card-header'>
                    <h4>04 Scrape Roster</h4>
                </div>
                <div class='card-body'>
                    <form>
                        <label>Team ID:</label>
                        <input type='text' id='04_teamid'>
                        <button class='btn' id='04_btn'>Scrape</button>
                    </form>
                </div>
                <div class='card-footer'></div>
            </div>
            

            
                

        </div>
        <div class="col-lg-2"></div>
    </div>

</body>

</html>