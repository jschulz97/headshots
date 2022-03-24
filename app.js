// Requires
const http = require('http');
// const url = require('url'); //parse url params
const fs = require('fs'); //file system
const mysql = require('mysql');
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');

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

con.query("SELECT * FROM camnotes.Teams where id=3;", function (err, result, fields) {
  if (err) throw err;
  url_base = result[0]['domain_base'];
  sport = result[0]['sport'];
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


// Express Server
const app = express();
app.use(cors({origin: '*'}));
// app.use(bodyParser.json());
app.use(bodyParser.json({limit: '150mb'}));

 

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
app.post('/send_iframe', function (req, res) {
  console.log('RECEIVED POST REQ')
  console.log(req.body);
  console.log(req.params);
  // fs.writeFile('./roster_test.html', req, () => {
  //   console.log('Done wiping headshots.html')
  // });
  res.end();
});


// FULL data re-grab
app.get('/admin', function (req, res) {
  text1 = `<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script>function send_iframe_data(){ 
    console.log("doing the ajax");
    var html_text = $('#learfield_window')[0];
    var empty;
    html_text.contentWindow.postMessage(empty, '*');
    var payload = html_text.contentWindow.document.slice(0,40);
    // console.log(html_text.contentWindow.document);
    $.ajax({
      type: 'POST',
      url: 'http://cams.schulzvideo.com/send_iframe',
      dataType: "json",
      data: '{"test_data":"'+payload+'"}',
      contentType: "application/json"
    });
    //$.post('http://cams.schulzvideo.com/send_iframe', {test_data:"test_value"});
  }</script>`
  text2 = '<button class="btn" onclick="send_iframe_data()">SEND IFRAME DATA</button><iframe id="learfield_window" style="width:100%; height:100%" src="https://gozags.com/sports/baseball/roster"/>'

  res.end(text1+text2);

  // fs.readFile('./index.html', function(err, data) {
  //   var sl_obj = new sl.Scrape_Learfield(done_scraping);
  //   var players = sl_obj.scrape(url_base, sport);

  //   res.write(data);
  //   res.end();
  // })
});


// Express listen
app.listen(port, function() {
  console.log(`Example app listening on port ${port}!`)
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