<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="hs_style.css">
    <link rel="stylesheet" href="https://fonts.sandbox.google.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- <link rel="stylesheet" type="text/css" href="assets/styles.css"> -->

    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

    <title>Cam Notes</title>
</head>

<style>

    @media print {
        @page {size: 19.5in 15in;}

        .dropdown {
            display: none;
        }

        .brand-break {
            display: none;
        }

    }

    .material-symbols-outlined {
        vertical-align: -5px;
        font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 20
    }

</style>


<body>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>


    <script>
        var dropdown_index_to_teams = [];


        function player_to_html(players) {
            full_html = '<div class="grid-container">\n';
            for(let i=0; i<players.length; i++) {
                // if(i % 5 == 0 && i != 0) {
                //   full_html = full_html + '</div><div class="grid-container">'
                // }

                html_pre = '<div class="grid-item">\n<div class="well">\n<div class="crop"><img src="';
                html_post = '" style=""/>\n</div>\n<div>';
                html_post = html_post + '<table><tr><td><strong><h4>' + players[i]['number'] + '</h4></strong> </td><td><div class="top-name">' + players[i]['f_name'] + '</div><div class="bottom-name">' + players[i]['l_name'] + '</div></td></tr></table></div>\n</div>\n</div>\n'
                full_html = full_html + html_pre + players[i]['headshot'] + html_post;
            }
            // full_html += '</div>';
            return full_html
        }


        function coach_to_html(players) {
            
            // full_html = '<div class="grid-container">\n';
            full_html = '';
            // var potential_style_text = 'style="grid-column-start: 1"';
            var potential_starting_style_text = '';
            for(let i=0; i<players.length; i++) {
                // if(i % 5 == 0 && i != 0) {
                //   full_html = full_html + '</div><div class="grid-container">'
                // }

                html_pre = '<div class="grid-item" '+ potential_starting_style_text +'>\n<div class="well coach-card">\n<div class="crop"><img src="';
                potential_starting_style_text = '';
                html_post = '" style=""/>\n</div>\n<div>';
                html_post = html_post + '<strong>' + players[i]['f_name'] + ' ' + players[i]['l_name'] + '</strong><br>' + players[i]['title'] + '</div>\n</div>\n</div>\n'
                full_html = full_html + html_pre + players[i]['headshot'] + html_post;
            }
            full_html += '</div>';
            return full_html
        }


        // main
        function populate_teams() {
            var game_id = getUrlParameter('game_id');

            $.get('http://node.cams.schulzvideo.com/get_game_by_id?game_id='+game_id, function(data, status) {
                var result = JSON.parse(data);

                // Team headers
                $('#team1_header').text(result[0]['team1_name']);
                $('#team2_header').text(result[0]['team2_name']);
                $('#team1_ddlabel').text(result[0]['team1_name']);
                $('#team2_ddlabel').text(result[0]['team2_name']);


                // Headshots
                // team 1 Coaches
                $.get('http://node.cams.schulzvideo.com/get_coaches_by_team_id?team_id='+result[0]['team1_id'], function(data, status) {
                    var team1_coaches = JSON.parse(data);

                    // team 1 players
                    $.get('http://node.cams.schulzvideo.com/get_players_by_team_id?team_id='+result[0]['team1_id'], function(data, status) {
                        var team1_players = JSON.parse(data);
                        $('#team1_half').html(player_to_html(team1_players)+coach_to_html(team1_coaches));
                    });
                });

                // team 2 Coaches
                $.get('http://node.cams.schulzvideo.com/get_coaches_by_team_id?team_id='+result[0]['team2_id'], function(data, status) {
                    var team2_coaches = JSON.parse(data);

                    // team 2 players
                    $.get('http://node.cams.schulzvideo.com/get_players_by_team_id?team_id='+result[0]['team2_id'], function(data, status) {
                        var team2_players = JSON.parse(data);
                        $('#team2_half').html(player_to_html(team2_players)+coach_to_html(team2_coaches));
                    });
                });

                

                
            });
        }


        // gets value of target url param
        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        }


        // function changeCss(className, classValue) {
        //     // we need invisible container to store additional css definitions
        //     var cssMainContainer = $('#css-modifier-container');
        //     if (cssMainContainer.length == 0) {
        //         var cssMainContainer = $('<div id="css-modifier-container"></div>');
        //         cssMainContainer.hide();
        //         cssMainContainer.appendTo($('body'));
        //     }

        //     // and we need one div for each class
        //     classContainer = cssMainContainer.find('div[data-class="' + className + '"]');
        //     if (classContainer.length == 0) {
        //         classContainer = $('<div data-class="' + className + '"></div>');
        //         classContainer.appendTo(cssMainContainer);
        //     }

        //     // append additional style
        //     classContainer.html('<style>' + className + ' {' + classValue + '}</style>');
        // }


        $('document').ready(function () {

            populate_teams();

            $("#team_view_dropdown").on('click', 'a', function(){
                // both teams
                if($(this).index() == 0) {
                    $('#team1').css('display','block');
                    $('#team2').css('display','block');
                    // changeCss('behave-like-bootstrap','grid-template-columns: 50% 50%;');
                } 
                // team 1
                else if ($(this).index() == 1) {
                    $('#team1').css('display','block');
                    $('#team2').css('display','none');
                    // changeCss('behave-like-bootstrap','grid-template-columns: 100%;');
                } 
                // team 2
                else if ($(this).index() == 2) {
                    $('#team2').css('display','block');
                    $('#team1').css('display','none');
                    // changeCss('behave-like-bootstrap','grid-template-columns: 100%;');
                }
            });

        });

    </script>



    <!--NAV BAR-->
    <?php include "sv_nav.html"; ?>
        <div class='grid-container' style='padding: 5px; margin-bottom: 5px; background-color: lightgray;'>
            <div class='row'>        
                <div class='col-sm-9 header-controls-left' style='margin-bottom: 7px;'>
                    <table><tr><td>
                        <div class="dropdown" id='team_view_dropdown'>
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">View Teams
                            <span class="caret"></span></button>
                            <ul id='team_view_selector' class="dropdown-menu">
                                <a class='dropdown-item' href='#'>Both</a>
                                <a id='team1_ddlabel' class='dropdown-item' href='#'>Team 1</a>
                                <a id='team2_ddlabel' class='dropdown-item' href='#'>Team 2</a>
                            </ul>
                        </div></td><td>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Select Filter
                            <span class="caret"></span></button>
                            <ul id='filter_players_view_dd' class="dropdown-menu">
                                <a id='' class='dropdown-item' href='#'>View All</a>
                                <a id='' class='dropdown-item' href='#'>Director's Choice</a>
                                <a id='' class='dropdown-item' href='#'>Custom Filter</a>
                            </ul>
                        </div></td></tr></table>
  
                </div>
                <div class='col-sm-3 header-controls-right' style='display:none;'>
                    
                        <button style='float: right;' class='btn btn-secondary' type='button' data-toggle='modal' data-target='#filter-players'>Edit Custom Filter &nbsp<span class="material-symbols-outlined">open_in_new</span></button>
                   
                </div>
            </div>
        </div>
    <div class='container'>
        
    </div>

    <div id='team-grid' class='grid-container behave-like-bootstrap'>
        <div id='team1' class="grid-item team_headshots">
            <h3 id='team1_header' class='team_header'>Team 1</h3>
            <div id='team1_half'></div>

            <div id='team1_brand_break'>
                <?php include "../assets/brand_break.html"; ?>
            </div>
        </div>

        <div id='team2' class="grid-item team_headshots">
            <h3 id='team2_header' class='team_header'>Team 2</h3>
            <div id='team2_half'></div>
        </div>

    </div>

    <div id='full_brand_break'>
        <?php include "../assets/brand_break.html"; ?>
    </div>



    <!-- The Modal -->
    <div class="modal" id="filter-players">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Filter Players (Coming soon...)</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div>
                <div class='grid-container'>
                    <div class='grid-item'>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Select Team
                            <span class="caret"></span></button>
                            <ul id='filter_players_team_dd' class="dropdown-menu">
                                <a id='filter_players_team1_ddlabel' class='dropdown-item' href='#'>Team 1</a>
                                <a id='filter_players_team2_ddlabel' class='dropdown-item' href='#'>Team 2</a>
                            </ul>
                        </div>
                    </div>
                    <div class='grid-item'>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Select Preset
                            <span class="caret"></span></button>
                            <ul id='filter_players_view_dd' class="dropdown-menu">
                                <a id='' class='dropdown-item' href='#'>View All</a>
                                <a id='' class='dropdown-item' href='#'>Director's Choice</a>
                                <a id='' class='dropdown-item' href='#'>Custom Filter</a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
    </div>




</body>