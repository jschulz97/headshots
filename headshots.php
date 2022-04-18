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

        function populate_teams() {

        }

        $('document').ready(function () {

            populate_teams();

            $(".dropdown").on('click', 'a', function(){
                // both teams
                if($(this).index() == 0) {
                    $('#team1_half').css('display','block');
                    $('#team2_half').css('display','block');
                } 
                // team 1
                else if ($(this).index() == 1) {
                    $('#team1_half').css('display','block');
                    $('#team2_half').css('display','none');
                } 
                // team 2
                else if ($(this).index() == 2) {
                    $('#team2_half').css('display','block');
                    $('#team1_half').css('display','none');
                }
            });
        });

    </script>



    <!--NAV BAR-->
    <?php include "sv_nav.html"; ?>
        <div class='grid-container' style='padding: 5px; margin-bottom: 5px; background-color: lightgray;'>
            <div class='grid-item'>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Select Teams
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <a class='dropdown-item' href='#'>Both</a>
                        <a class='dropdown-item' href='#'>Team 1</a>
                        <a class='dropdown-item' href='#'>Team 2</a>
                    </ul>
                </div>
            </div>
            <div class='grid-item'>
                
            </div>
        </div>
    <div class='container'>
        
    </div>

    <div class='row'>
        <div id='team1_half' class="col-md-6 team_headshots">
            <h3 id='team1_header' class='team_header'>Team 1</h3>
            <div class="grid-container">
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/00_Lauren_Mathis.png" style=""/>
            </div>
            <div><strong>00</strong> Lauren<br>Mathis</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/1_Savana_Sikes.png" style=""/>
            </div>
            <div><strong>1</strong> Savana<br>Sikes</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/2_Aniyah_Black.png" style=""/>
            </div>
            <div><strong>2</strong> Aniyah<br>Black</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/3_Jaiden_Fields.png" style=""/>
            </div>
            <div><strong>3</strong> Jaiden<br>Fields</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/4_Faith_Barth.png" style=""/>
            </div>
            <div><strong>4</strong> Faith<br>Barth</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/5_Sydney_Osada.png" style=""/>
            </div>
            <div><strong>5</strong> Sydney<br>Osada</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/6_Sydney_Kuma.png" style=""/>
            </div>
            <div><strong>6</strong> Sydney<br>Kuma</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/7_Mackenzie_Puckett.png" style=""/>
            </div>
            <div><strong>7</strong> Mackenzie<br>Puckett</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/8_Jayda_Kearney.png" style=""/>
            </div>
            <div><strong>8</strong> Jayda<br>Kearney</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/9_Sydney_Chambley.png" style=""/>
            </div>
            <div><strong>9</strong> Sydney<br>Chambley</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/9_Sydney_Chambley.png" style=""/>
            </div>
            <div><strong>10</strong> Riley<br>Orcutt</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/11_Lyndi_Rae_Davis.png" style=""/>
            </div>
            <div><strong>11</strong> Lyndi<br>Rae Davis</div>
            </div>
            </div>
            <div class="grid-item">
                <div class="well">
                    <div class="crop"><img src="https://ddc2kvg5ezaga.cloudfront.net/images/2021/10/7/12_Kylie_Macy.png" style=""/></div>
                    <div><strong>12</strong> Kylie<br>Macy</div>
                </div>
            </div>
            </div>

            <div id='team1_brand_break'>
                <?php include "../assets/brand_break.html"; ?>
            </div>
        </div>

        <div id='team2_half' class="col-md-6 team_headshots">
            <h3 id='team2_header' class='team_header'>Team 2</h3>
            <div class="grid-container">
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/0_Cook_Kendal_01_2.jpg" style=""/>
            </div>
            <div><strong>0</strong> Kendal<br>Cook</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/2_Bailey_Kendyll_01_2.jpg" style=""/>
            </div>
            <div><strong>2</strong> Kendyll<br>Bailey</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/3_Laird_Jenna_01_2.jpg" style=""/>
            </div>
            <div><strong>3</strong> Jenna<br>Laird</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/5_Nichols_Emma_01_2.jpg" style=""/>
            </div>
            <div><strong>5</strong> Emma<br>Nichols</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/6_Snider_Maddie_01_2.jpg" style=""/>
            </div>
            <div><strong>6</strong> Maddie<br>Snider</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/7_Wilmes_Brooke_01_2.jpg" style=""/>
            </div>
            <div><strong>7</strong> Brooke<br>Wilmes</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/8_McGivern_Hannah_01_2.jpg" style=""/>
            </div>
            <div><strong>8</strong> Hannah<br>McGivern</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/10_Moll_Megan_01_2.jpg" style=""/>
            </div>
            <div><strong>10</strong> Megan<br>Moll</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/11_Crenshaw_Julia_01_2.jpg" style=""/>
            </div>
            <div><strong>11</strong> Julia<br>Crenshaw</div>
            </div>
            </div>
            <div class="grid-item">
            <div class="well">
            <div class="crop"><img src="https://d98lmo17970r8.cloudfront.net/images/2021/9/29/12_Chaumont_Casidy_01_2.jpg" style=""/>
            </div>
            <div><strong>12</strong> Casidy<br>Chaumont</div>
            </div>
            </div>
            </div>

           
        
        </div>
        </div>

    </div>

    <div id='full_brand_break'>
        <?php include "../assets/brand_break.html"; ?>
    </div>

</body>