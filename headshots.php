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
    <!-- <link rel="stylesheet" type="text/css" href="assets/styles.css"> -->

    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

    <title>SV Headshots</title>
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
                html_post = html_post + '<strong>' + players[i]['number'] + '</strong> ' + players[i]['f_name'] + '<br>' + players[i]['l_name'] + '</div>\n</div>\n</div>\n'
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
                // team 1 players
                $.get('http://node.cams.schulzvideo.com/get_players_by_team_id?team_id='+result[0]['team1_id'], function(data, status) {
                    var team1_players = JSON.parse(data);
                    $('#team1_half').html(player_to_html(team1_players));
                });

                // team 2 players
                $.get('http://node.cams.schulzvideo.com/get_players_by_team_id?team_id='+result[0]['team2_id'], function(data, status) {
                    var team2_players = JSON.parse(data);
                    $('#team2_half').html(player_to_html(team2_players));
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


        $('document').ready(function () {

            populate_teams();

            $(".dropdown").on('click', 'a', function(){
                // both teams
                if($(this).index() == 0) {
                    $('#team1').css('display','block');
                    $('#team2').css('display','block');
                } 
                // team 1
                else if ($(this).index() == 1) {
                    $('#team1').css('display','block');
                    $('#team2').css('display','none');
                } 
                // team 2
                else if ($(this).index() == 2) {
                    $('#team2').css('display','block');
                    $('#team1').css('display','none');
                }
            });

        });


        

    </script>



    <!--NAV BAR-->
    <?php include "sv_nav.html"; ?>
        <div class='grid-container' style='padding: 5px; margin-bottom: 5px; background-color: lightgray;'>
            <div class='grid-item'>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">View Teams
                    <span class="caret"></span></button>
                    <ul id='team_view_selector' class="dropdown-menu">
                        <a class='dropdown-item' href='#'>Both</a>
                        <a id='team1_ddlabel' class='dropdown-item' href='#'>Team 1</a>
                        <a id='team2_ddlabel' class='dropdown-item' href='#'>Team 2</a>
                    </ul>
                </div>
            </div>
            <div class='grid-item'>
                
            </div>
        </div>
    <div class='container'>
        
    </div>

    <div class='row'>
        <div id='team1' class="col-md-6 team_headshots">
            <h3 id='team1_header' class='team_header'>Team 1</h3>
            <div id='team1_half'></div>

            <div id='team1_brand_break'>
                <?php include "../assets/brand_break.html"; ?>
            </div>
        </div>

        <div id='team2' class="col-md-6 team_headshots">
            <h3 id='team2_header' class='team_header'>Team 2</h3>
            <div id='team2_half'></div>
        </div>

    </div>

    <div id='full_brand_break'>
        <?php include "../assets/brand_break.html"; ?>
    </div>

</body>