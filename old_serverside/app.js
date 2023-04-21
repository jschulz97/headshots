// Requires
const http = require('http');
const https = require('https');

const fs = require('fs'); //file system
const mysql = require('mysql');
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
var formidable = require('formidable');

const sl = require('./scrape_learfield');
const dbqb = require('./db_query_builders.js');
const { resourceLimits } = require('worker_threads');
const { join } = require('path');
const { addAbortSignal } = require('stream');
const { query } = require('express');
const { Console } = require('console');


// Okta AUTH
// Client ID
//0oa4fhgs9t9mVOxMq5d7
// Domain
//dev-99228383.okta.com




// Parameters
const hostname = '0.0.0.0';
const port = 3030;


// MySQL
var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "%p1nnJI*"
});

con.connect(function(err) {
    if (err) throw err;
    console.log("Connected!");
});

var url_base;
var sport;
var players_count    = 0;

var sv_header;
var sv_nav;

// con.query("SELECT * FROM camnotes.Teams where id=3;", function (err, result, fields) {
//     if (err) throw err;
//     url_base = result[0]['domain_base'];
//     sport = result[0]['sport'];
// });


// // HTML Assets
// fs.readFile('./sv_header.htmlx', function(err, data) {
//     sv_header = data;
//     console.log('read sv_header');
// });

// fs.readFile('./sv_nav.htmlx', function(err, data) {
//     sv_nav = data;
//     console.log('read sv_nav');
// });


// HTML
default_headshots_html = `
    <html>
    <style>
        .well {
            min-height: 20px;
            padding: 5px;
            margin-bottom: 5px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
        }
        .grid-container {
            display: grid;
            grid-auto-flow: row;
            grid-template-columns: repeat(7, 130px);
            text-align: center;
        }
    </style>
    <body>
`;

dropdown_head = `<div class="dropdown">
<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Sport:
<span class="caret"></span></button>
<div class="dropdown-menu">`;
dropdown_tail = '</div></div>'



// Express Server
const app = express();
app.use(cors({origin: '*'}));
app.set('trust proxy', true);
// app.use(bodyParser.json());
app.use(bodyParser.json({limit: '150mb'}));


// // Home Page
// var team_names;
// var team_ids;
// app.get('/', function(req, res) {
//     fs.readFile('./index.htmlx', function(err, data) {
//         team_names = [];
//         team_ids = [];
//         con.query("SELECT * FROM camnotes.Sports;", function (err, result, fields) {
//             if (err) throw err;
//             var text = '';

//             for(var i=0; i<result.length; i++) {
//                 team_names.push(result[i]['name']);
//                 team_ids.push(result[i]['id']);
//                 text += ' <a class="dropdown-item" href="#">' + result[i]['name'] + '</a>';
//             }

//             res.end(sv_header+sv_nav+dropdown_head+text+dropdown_tail);
//         });
        
//     });
// });


// // Display printable headshots
// app.get('/headshots', function(req, res) {
//     var subpage = req.url.slice(1,req.url.length)
//     var text = "Loading game " + subpage + "...";
//     console.log('doing headshots?')
//     fs.readFile('./headshots.html', function(err, data) {
//         res.write(data);
//         res.end();
//     });
// });


// // FULL data re-grab
// app.get('/redo_data', function (req, res) {
//     fs.writeFile('./headshots.html', default_headshots_html, () => {
//         console.log('Done wiping headshots.html')
//     });

//     fs.readFile('./index.html', function(err, data) {
//         var sl_obj = new sl.Scrape_Learfield(done_scraping);
//         var players = sl_obj.scrape(url_base, sport);

//         res.write(data);
//         res.end();
//     })
// });


app.post('/send_raw_roster', function (req, res) {
    console.log('RECEIVED POST REQ')
    // console.log(req.body);
    // console.log(req.params);
    var form = new formidable.IncomingForm();
    form.parse(req, function (err, fields, files) {
        var oldpath = files.filetoupload.filepath;
        var time = new Date().getTime().toString();
        var newpath = '/www/schulzvideo.com/public_html/headshots/sites/' + time + '.txt';
        con.query(`
            UPDATE camnotes.Teams
            SET dir_html='`+newpath+`'
            WHERE id=`+fields.team_id+`;
        `, function (err, result, fields) {
            if (err) throw err;
        });
        console.log('Uploading raw roster for team id: '+fields.team_id);
        fs.rename(oldpath, newpath, function (err) {
            if (err) throw err;
            res.end('<html><h3>File uploaded and moved!</h3><a href="http://cams.schulzvideo.com/admin" role="button"><button type="button" class="btn">Back to Admin Page</button></a></html>');
        });
    });
});


// Admin page


// get game info by game id
app.get('/get_game_by_id', function (req,res) {
	con.query(`
        SELECT Games.team1_id, T1.name team1_name, Games.team2_id, T2.name team2_name, Sports.name sport
        FROM camnotes.Games Games
        JOIN camnotes.Teams T1 ON Games.team1_id = T1.id 
        JOIN camnotes.Teams T2 ON Games.team2_id = T2.id
        JOIN camnotes.Sports Sports ON Games.sport_id = Sports.id
		WHERE Games.id=`+req.query.game_id+';'
    , function (err, result, fields) {
        if (err) throw err;
        res.end(JSON.stringify(result));
    });
});


// get players by team id
app.get('/get_players_by_team_id', function (req,res) {
    console.log('GET: get_players_by_team_id: '+req.query.team_id);
	con.query(
        // `SELECT Players.id, Players.team_id, Players.f_name, Players.l_name, Players.height_f, 
        //     Players.height_i, Players.position, Players.year, Players.bats, LEFT(Players.number,3) number AS INT,
        //     Players.headshot
        // FROM camnotes.Players Players
		// WHERE Players.team_id=`+req.query.team_id+`
        // ORDER BY number DESC`+';'
        `SELECT *
        FROM camnotes.Players Players
		WHERE Players.team_id=`+req.query.team_id+`
        ORDER BY CAST(Players.number AS unsigned) ASC`+';'
    , function (err, result, fields) {
        if (err) throw err;
        res.end(JSON.stringify(result));
    });
});


// get coaches by team id
app.get('/get_coaches_by_team_id', function (req,res) {
    console.log('GET: get_coaches_by_team_id: '+req.query.team_id);
	con.query(
        `SELECT *
        FROM camnotes.Coaches Coaches
		WHERE Coaches.team_id=`+req.query.team_id+`
        ORDER BY Coaches.priority ASC`+';'
    , function (err, result, fields) {
        if (err) throw err;
        res.end(JSON.stringify(result));
    });
});


// Add new coach
app.get('/insert_coach', function (req, res) {
    console.log('GET: insert_coach: '+req.query.f_name+' '+req.query.l_name)
    con.query(
        dbqb.db_insert_query('camnotes.Coaches',
            ['team_id','f_name','l_name','priority','title','short_title','headshot'],
            [req.query.team_id, req.query.f_name, req.query.l_name, req.query.priority, 
             req.query.title, req.query.short_title, req.query.headshot])
        , function (err, result, fields) {
        if (err) throw err;
        res.end();
    });
});


// Update coach
app.get('/update_coach', function (req, res) {
    console.log('GET: update_coach: '+req.query.f_name+' '+req.query.l_name)
    con.query(
        dbqb.db_update_query(
            'camnotes.Coaches',
            ['f_name','l_name','priority','title','short_title','headshot'],
            [req.query.f_name, req.query.l_name, req.query.priority, 
                req.query.title, req.query.short_title, req.query.headshot],
            'id='+req.query.id)
        , function (err, result, fields) {
        if (err) throw err;
        res.end();
    });
});


// Delete Coach
app.get('/delete_coach', function (req, res) {
    console.log('GET: delete_coach: '+req.query.id)
    con.query(
        `DELETE FROM camnotes.Coaches WHERE id=`+req.query.id
        , function (err, result, fields) {
        if (err) throw err;
        res.end();
    });
});


// Get List of recent Games
app.get('/get_recent_games', function (req, res) {
    // SELECT name FROM Teams WHERE id=(SELECT team1_id FROM Games WHERE id=2);
    console.log('GET: get_recent_games '+req.ip)
    con.query(`
        SELECT Games.id, Games.date, T1.name t1name, T2.name t2name, Sports.name sport
        FROM camnotes.Games Games
        JOIN camnotes.Teams T1 ON Games.team1_id = T1.id 
        JOIN camnotes.Teams T2 ON Games.team2_id = T2.id
        JOIN camnotes.Sports Sports ON Games.sport_id = Sports.id;`
    , function (err, result, fields) {
        if (err) throw err;
        res.end(JSON.stringify(result));
    });
});


// Get List of teams
app.get('/get_teams', function (req, res) {
    // SELECT name FROM Teams WHERE id=(SELECT team1_id FROM Games WHERE id=2);
    console.log('GET: get_teams: '+req.query.name)
    con.query(`
        SELECT Teams.id id, Teams.name name, Sports.name sport
        FROM camnotes.Teams Teams
        JOIN camnotes.Sports Sports ON Teams.sport = Sports.id
        WHERE Teams.name LIKE '%`+req.query.name+`%';`
    , function (err, result, fields) {
        if (err) throw err;
        res.end(JSON.stringify(result));
    });
});


// 03 Add Team
app.get('/add_team', function (req, res) {
    console.log('GET: add_team: '+req.query.name)
    con.query(`INSERT INTO camnotes.Teams (name,sport,base_url) VALUES ('`+req.query.name+`', '`+req.query.sport+`', '`+req.query.base_url+`');`, function (err, result, fields) {
        if (err) throw err;
        con.query(`
            SELECT Teams.id id, Teams.name name, Sports.name sport, Teams.base_url base_url
            FROM camnotes.Teams Teams
            JOIN camnotes.Sports Sports ON Teams.sport = Sports.id
            WHERE Teams.name='`+req.query.name+`' AND Teams.sport=`+req.query.sport+';'
        , function (err, result, fields) {
            if (err) throw err;
            res.end(JSON.stringify(result));
        });
    });
});

// 01 Add Game
app.get('/create_game', function (req, res) {
    console.log('GET: create_game ')
    con.query(`INSERT INTO camnotes.Games (team1_id,team2_id,sport_id,date) VALUES ('`+req.query.team1_id+`', '`+req.query.team2_id+`', '`+req.query.sport_id+`', '`+req.query.date+`');`, function (err, result, fields) {
        if (err) throw err;
		new_query = `
		SELECT Games.id id, T1.name team1_name, T2.name team2_name, Sports.name sport, Games.date date
		FROM camnotes.Games Games
		JOIN camnotes.Sports Sports ON Games.sport_id = Sports.id
		JOIN camnotes.Teams T1 ON Games.team1_id = T1.id
		JOIN camnotes.Teams T2 ON Games.team2_id = T2.id
		WHERE Games.team1_id=`+req.query.team1_id+` AND Games.team2_id=`+req.query.team2_id+` AND Games.sport_id=`+req.query.sport_id+` AND Games.date='`+req.query.date+ `';`;
		console.log(new_query)
        con.query(new_query
        , function (err, result, fields) {
            if (err) throw err;
            res.end(JSON.stringify(result));
        });
    });
});


// Get List of teams
app.get('/get_sports', function (req, res) {
    // SELECT name FROM Teams WHERE id=(SELECT team1_id FROM Games WHERE id=2);
    console.log('GET: get_sports '+req.query.name)
    con.query(`
        SELECT *
        FROM camnotes.Sports;`
    , function (err, result, fields) {
        if (err) throw err;
        res.end(JSON.stringify(result));
    });
});


app.get('/execute_scrape', function(req, res) {
    // fs.readFile('./default_headshots.htmlx', function(err,data) {
    //     fs.writeFile('./'+req.query.team_id+'.html', data, () => {
    //         console.log('Done initializing '+req.query.team_id+'.html')
    //     });
    // });

    var sl_obj = new sl.Scrape_Learfield(done_scraping_players, done_scraping_coaches);
    con.query(` SELECT Teams.dir_html, Sports.name, Teams.base_url
                            FROM camnotes.Teams Teams 
                            JOIN camnotes.Sports Sports ON Teams.sport = Sports.id
                            WHERE Teams.id=`+req.query.team_id+`;`
    , function (err, result, fields) {
        if (err) throw err;
        var players = sl_obj.scrape(req.query.team_id, result[0]['dir_html'], result[0]['base_url']);
    }); 
});


// FULL data re-grab
app.get('/admin/upload_roster', function (req, res) {
    fs.readFile('./upload_roster.htmlx', function(err, data) {
        res.end(data);
    });

    // res.end(text3);

    // fs.readFile('./index.html', function(err, data) {
    //     var sl_obj = new sl.Scrape_Learfield(done_scraping);
    //     var players = sl_obj.scrape(url_base, sport);

    //     res.write(data);
    //     res.end();
    // })
});


// Express listen
app.listen(port, function() {
    console.log(`SVJSHeadshots listening on port ${port}.`)
});

// // Create an HTTP service.
// http.createServer(app).listen(3030);
// // Create an HTTPS service identical to the HTTP service.
// var options = {
//     key: fs.readFileSync('/etc/letsencrypt/live/schulzvideo.com/privkey.pem'),
//     cert: fs.readFileSync('/etc/letsencrypt/live/schulzvideo.com/fullchain.pem')
//   };
// https.createServer(options, app).listen(3031);


app.get('/test_query', function (req, res) {
    //test
});




// Handle player data, commit to DB
function done_scraping_players(player_urls, player_list) {

	// for all players
	for(let i in player_list) {
		// console.log(i+' '+player_list[i]['fname']);
		con.query(dbqb.db_select_query(
			'camnotes.Players',
			['id'],
			[
				['team_id','f_name','l_name'],
				[player_list[i]['team_id'],player_list[i]['fname'],player_list[i]['lname']]
			])
		, function(err, result, fields) { 
			if (err) throw err; 

			// if player doesn't exist, insert
			if(result.length == 0) {
				
				con.query(dbqb.db_insert_query(
					'camnotes.Players',
					['team_id','f_name','l_name','height_f','height_i','position','year','bats','number','headshot'],
					[player_list[i]['team_id'],player_list[i]['fname'],player_list[i]['lname'],player_list[i]['height_f'],player_list[i]['height_i'],
					player_list[i]['position'],player_list[i]['year'],player_list[i]['bats'],
					player_list[i]['number'],player_urls[i]])
				, function(err, result, fields) { 
					if (err) throw err; 
					console.log('successful insert: ' + player_list[i]['number'] + ' ' + player_list[i]['lname'])
				}); 
			} 
			else

			// if player exists, update
			{
				con.query(dbqb.db_update_query(
					'camnotes.Players',
					['team_id','height_f','height_i','position','year','bats','number','headshot'],
					[player_list[i]['team_id'],player_list[i]['height_f'],player_list[i]['height_i'],
					player_list[i]['position'],player_list[i]['year'],player_list[i]['bats'],
					player_list[i]['number'],player_urls[i]],
					'id='+result[0]['id'])
				, function(err, result, fields) { 
					if (err) throw err; 
					console.log('successful update: '+ player_list[i]['number'] + ' ' +player_list[i]['lname'])
				}); 
			}
		}); 
	}

    console.log('Players Finished');
}





// Commit coaches data to DB
function done_scraping_coaches(coach_list) {

	// for all coaches
	for(let i in coach_list) {
		con.query(dbqb.db_select_query(
			'camnotes.Coaches',
			['id'],
			[
				['team_id','f_name','l_name'],
				[coach_list[i]['team_id'],coach_list[i]['fname'],coach_list[i]['lname']]
			])
		, function(err, result, fields) { 
			if (err) throw err; 

			// if coach doesn't exist, insert
			if(result.length == 0) {
				
				con.query(dbqb.db_insert_query(
					'camnotes.Coaches',
					['team_id','f_name','l_name','title','short_title','priority','headshot'],
					[coach_list[i]['team_id'],coach_list[i]['fname'],coach_list[i]['lname'],coach_list[i]['title'],coach_list[i]['short_title'],
					coach_list[i]['priority'],coach_list[i]['url']])
				, function(err, result, fields) { 
					if (err) throw err; 
					console.log('successful insert: ' + coach_list[i]['fname'] + ' ' + coach_list[i]['lname'])
				}); 
			} 
			else

			// if player exists, update
			{
				con.query(dbqb.db_update_query(
					'camnotes.Coaches',
					['team_id','f_name','l_name','title','short_title','priority','headshot'],
					[coach_list[i]['team_id'],coach_list[i]['fname'],coach_list[i]['lname'],coach_list[i]['title'],coach_list[i]['short_title'],
					coach_list[i]['priority'],coach_list[i]['url']],
					'id='+result[0]['id'])
				, function(err, result, fields) { 
					if (err) throw err; 
					console.log('successful update: '+ coach_list[i]['fname'] + ' ' +coach_list[i]['lname'])
				}); 
			}
		}); 
	}

    console.log('Coaches Finished');
}



