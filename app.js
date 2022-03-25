// Requires
const http = require('http');
const fs = require('fs'); //file system
const mysql = require('mysql');
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
var formidable = require('formidable');

const sl = require('./scrape_learfield');
const { resourceLimits } = require('worker_threads');
const { join } = require('path');


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
var players_count  = 0;

var sv_header;
var sv_nav;

// con.query("SELECT * FROM camnotes.Teams where id=3;", function (err, result, fields) {
//   if (err) throw err;
//   url_base = result[0]['domain_base'];
//   sport = result[0]['sport'];
// });


// HTML Assets
fs.readFile('./sv_header.htmlx', function(err, data) {
  sv_header = data;
  console.log('read sv_header');
});

fs.readFile('./sv_nav.htmlx', function(err, data) {
  sv_nav = data;
  console.log('read sv_nav');
});


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
// app.use(bodyParser.json());
app.use(bodyParser.json({limit: '150mb'}));


// Home Page
var team_names;
var team_ids;
app.get('/', function(req, res) {
  fs.readFile('./index.htmlx', function(err, data) {
    team_names = [];
    team_ids = [];
    con.query("SELECT * FROM camnotes.Sports;", function (err, result, fields) {
      if (err) throw err;
      var text = '';

      for(var i=0; i<result.length; i++) {
        team_names.push(result[i]['name']);
        team_ids.push(result[i]['id']);
        text += ' <a class="dropdown-item" href="#">' + result[i]['name'] + '</a>';
      }

      res.end(sv_header+sv_nav+dropdown_head+text+dropdown_tail);
    });
    
  });
});


// Display printable headshots
app.get('/headshots', function(req, res) {
  var subpage = req.url.slice(1,req.url.length)
  var text = "Loading game " + subpage + "...";
  console.log('doing headshots?')
  fs.readFile('./headshots.html', function(err, data) {
    res.write(data);
    res.end();
  });
});


// FULL data re-grab
app.get('/redo_data', function (req, res) {
  fs.writeFile('./headshots.html', default_headshots_html, () => {
    console.log('Done wiping headshots.html')
  });

  fs.readFile('./index.html', function(err, data) {
    var sl_obj = new sl.Scrape_Learfield(done_scraping);
    var players = sl_obj.scrape(url_base, sport);

    res.write(data);
    res.end();
  })
});


// FULL data re-grab
app.post('/send_raw_roster', function (req, res) {
  console.log('RECEIVED POST REQ')
  // console.log(req.body);
  // console.log(req.params);
  var form = new formidable.IncomingForm();
  form.parse(req, function (err, fields, files) {
    console.log(files)
    var oldpath = files.filetoupload.filepath;
    var time = new Date().getTime().toString();
    var newpath = '/www/jschulz.dev/headshots/sites/' + time + '.txt';
    fs.rename(oldpath, newpath, function (err) {
      if (err) throw err;
      res.end('<html><h3>File uploaded and moved!</h3><a href="/admin" role="button"><button type="button" class="btn">Back to Admin Page</button></a></html>');
      // res.end('<html><meta http-equiv="Refresh" content="0; url=http://cams.schulzvideo.com/admin"/>');

    });
  });
});


// Admin page
app.get('/admin', function (req, res) {
  fs.readFile('./admin.htmlx', function(err, data) {
    res.end(sv_header+sv_nav+data);
  });
});



// FULL data re-grab
app.get('/admin/upload_roster', function (req, res) {

  text = `<html>
    <form action="http://cams.schulzvideo.com/send_raw_roster" enctype="multipart/form-data" method="POST">
    <input type="file" class="admin__input" name="filetoupload" />
    <input class="admin__submit" type="submit" />
  </form>
  `

  res.end(text);

  // res.end(text3);

  // fs.readFile('./index.html', function(err, data) {
  //   var sl_obj = new sl.Scrape_Learfield(done_scraping);
  //   var players = sl_obj.scrape(url_base, sport);

  //   res.write(data);
  //   res.end();
  // })
});


// Express listen
app.listen(port, function() {
  console.log(`SVJSHeadshots listening on port ${port}.`)
});



// Build headshots html file
function done_scraping(player_urls, player_list) {
  full_html = '<div class="grid-container">\n';
  for(let i=0; i<player_urls.length; i++) {
    // if(i % 5 == 0 && i != 0) {
    //   full_html = full_html + '</div><div class="grid-container">'
    // }

    html_pre = '<div class="grid-item">\n<div class="well">\n<img src="';
    html_post = '" style="max-width:100%"/>\n<div>';
    html_post = html_post + '<strong>' + player_list[i].number + '</strong> ' + player_list[i].fname + ' ' + player_list[i].lname + '</div>\n</div>\n</div>\n'
    full_html = full_html + html_pre + player_urls[i] + html_post;
  }
  fs.appendFile('./headshots.html', full_html, ()=>{});
  console.log('Headshots Finished')
}