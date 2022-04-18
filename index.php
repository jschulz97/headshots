<!--Header-->
<?php include "sv_header.html"; ?>



<script>
    // Maps dropdown indices to game ids
    var dropdown_index_to_gameid = [];

    // Populates dropdown on load
    function populate_game_dropdown_list() {
        $.get('http://node.cams.schulzvideo.com/get_recent_games', function(data, status) {
            var result = JSON.parse(data);

            // get saved gameid if there's one
            var cookie_gameid = get_cookie('camnotes_gameid');
            var id;
            if(cookie_gameid == false) {
                id = result[0]['id'];
            } else {
                id = cookie_gameid;
            }
            
            // build dropdown 
            var built_html = '';
            var get_local_index_from_id; 
            for(var i=0; i<result.length; i++) {
                if(result[i]['id'] == id) {
                    get_local_index_from_id = i;
                }
                var d = new Date(result[i]['date']);
                built_html += '<a class="dropdown-item" href="#">' + d.toDateString() + ' - ';
                built_html += result[i]['t1name'] + ' vs ';
                built_html += result[i]['t2name'] + ' ';
                built_html += result[i]['sport'] + '</a>';
                dropdown_index_to_gameid.push(result[i]['id']);
            }

            // apply to dropdown on page
            $('#game_select_parent').html(built_html);

            // build string for dropdown button label
            var i = get_local_index_from_id;

            $('#game_select_button').text(new Date(result[i]['date']).toDateString() + 
                ' - ' + result[i]['t1name'] + ' vs ' + 
                result[i]['t2name'] + ' ' + result[i]['sport']);
            
            // make cookie
            // Expire 24 hours
            modify_cookie('camnotes_gameid', id, 7*24*60*60*1000);
            console.log('Game ID '+id+' populated');
        });
    }

    // Modify cookie by name
    // Delete cookie with negative expire_seconds
    function modify_cookie(name, value, expire_seconds) {
        var d = new Date();
        var expiry = d.getTime().valueOf()+expire_seconds.valueOf();
        var d = new Date(expiry);
        document.cookie = name + '=' + value + '; expires=' + d.toUTCString() + '; path=/;';
    }

    // Searches browser for cookie value by name
    function get_cookie(name, value, expire_seconds) {
        var cookie_string = document.cookie.split(';');
        for(var i=0; i<cookie_string.length; i++) {
            if(cookie_string[i].includes('camnotes_gameid')) {
                return cookie_string[i].split('=')[1].valueOf();
            }
        }
        return false;
    }

    // Document load
    $('document').ready( function() {
        dropdown_index_to_gameid = [];
        populate_game_dropdown_list();
        
        // Change game dropdown selection
        $(".dropdown").on('click', 'a', function(){
            $("#game_select_button:first-child").text($(this).text());
            $("#game_select_button:first-child").val($(this).text());
            modify_cookie('camnotes_gameid', dropdown_index_to_gameid[$(this).index()], 7*24*60*60*1000);
        });
    });

</script>

<!--NAV BAR-->
<?php include "sv_nav.html"; ?>

<!--BODY-->
<div class="text-center">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">

            <br>
            <h5>Homepage of Camera Notes</h5>

            <br>
            <br>

            <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                    <div class='card'>
                        <div class='card-header'>
                            <h5>Select Game:</h5>
                            <div class="dropdown">
                                <button id='game_select_button' class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Game<span class="caret"></span></button>
                                <div id='game_select_parent' class="dropdown-menu">
                                    <a class="dropdown-item" href="#">No games loaded</a>
                                </div>
                            </div>
                        </div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class="col-sm-6">
                                    <a href="roster.php">
                                        <div id="this_div_here" class="card-blue card">
                                            <br>
                                            <img class='mx-auto d-block card-img-top card-icon' src='./assets/icon_list.png'>
                                            <br>
                                            <div class='card-header'>
                                                <h6>Roster</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="headshots.php">
                                        <div class="card-blue card">
                                            <br>
                                            <img class='mx-auto d-block card-img-top card-icon' src='assets/icon_face.png'>
                                            <br>
                                            <div class='card-header'>
                                                <h6>Headshots</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2"></div>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
</div>


</body>

</html>