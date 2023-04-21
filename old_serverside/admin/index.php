<!--Header-->
<?php include "../sv_header.html"; ?>


<!--NAV BAR-->
<?php include "../sv_nav.html"; ?>


<script>
    // do one function with api call and table input to list everything in results
    // api needs to return table column names?

    $('document').ready(function() {
        $('#00_btn').click(function(e) {
            e.preventDefault();
            populate_table('http://node.cams.schulzvideo.com/get_recent_games', $('#00'),
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

        $('#01_btn').click(function(e) {
            e.preventDefault();
            var sport = $('#01_sport').val();
            var team_1 = $('#01_team1').val();
            var team_2 = $('#01_team2').val();
            var date = $('#01_date').val();
            populate_table('http://node.cams.schulzvideo.com/create_game?team1_id='+team_1+'&team2_id='+team_2+'&sport_id='+sport+'&date='+date, $('#01'),
                ['ID','Team 1','Team 2','Sport','Date'],
                ['id','team1_name','team2_name','sport','date']);
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


        function pretty_date(date) {
            date_str = new Date(date).toUTCString();
            arr = date_str.split(' '); 
            return arr[0]+' '+arr[1]+' '+arr[2]+' '+arr[3];
        }


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
                            val = pretty_date(result[i]['date']);
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
            var team_base_url = $('#03_base_url').val();
            populate_table('http://node.cams.schulzvideo.com/add_team?name='+team_name+'&sport='+team_sport+'&base_url='+team_base_url, $('#03'),
                ['ID','Name','Sport','Base URL'],
                ['id','name','sport','base_url']);
        });


        $('#04_btn').click(function(e) {
            e.preventDefault();
            var team_id = $('#04_teamid').val();
            $.get('http://node.cams.schulzvideo.com/execute_scrape?team_id='+team_id, function(data, status) {
                
            });
        });


    });

    // Range for shifting headshots on card
    //<input id='img-shift-range' type="range" class="form-range " min="-100" max="100">
    var default_img_shift = -80;
    $(document).on('input', '#img-shift-range', function() {
        console.log(parseInt(default_img_shift)+parseInt($(this).val()) + '%');
        $('.crop img').css('top',  parseInt(default_img_shift)+parseInt($(this).val()) + '%')
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

                
            <div class='card' id='00'>
                <div class='card-header'>
                    <h4>00 Show Games</h4>
                </div>
                <div class='card-body'>
                    <button class='btn' id='00_btn'>Display</button>
                </div>
                <div class='card-footer'>
                </div>
            </div>

            <div class='card' id='01'>
                <div class='card-header'>
                    <h4>01 Create Game</h4>
                </div>
                <div class='card-body'>
                    <form>
                        <table>
                            <tr><td>
                                <label>Sport ID:</label></td><td>
                                <input type='text' id='01_sport'>
                            </td></tr><tr><td>
                                <label>Team 1 ID:</label></td><td>
                                <input type='text' id='01_team1'>
                            </td></tr><tr><td>
                                <label>Team 2 ID:</label></td><td>
                                <input type='text' id='01_team2'>
                            </td></tr><tr><td>
                                <label>Date (YYYY-MM-DD): &nbsp</label></td><td>
                                <input type='text' id='01_date'> 
                            </td></tr>
                        </table>
                        <button class='btn' id='01_btn'>Create Game</button>
                    </form>
                </div>
                <div class='card-footer'></div>
            </div>

            <div class='card' id='02'>
                <div class='card-header'>
                    <h4>02 Search Teams</h4>
                </div>
                <div class='card-body'>
                    <form>
                        <label>Team Name: &nbsp</label>
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
                        <table><tr>
                                <td><label>Team Name: &nbsp</label></td>
                                <td><input type='text' id='03_name'></td></tr>
                                <tr><td><label>Sport ID:</label></td>
                                <td><input type='text' id='03_sport'></td></tr>
                                <tr><td><label>Base URL:</label></td>
                                <td><input type='text' id='03_base_url'></td></tr>
                        </table>
                        <button class='btn' id='03_btn'>Add Team</button>
                    </form>
                    <br>
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
                        <label>Team ID: &nbsp</label>
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