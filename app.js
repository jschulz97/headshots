const http = require('http');
// const url = require('url'); //parse url params
const fs = require('fs'); //file system
const mysql = require('mysql');

const sl = require('./scrape_learfield');
const { resourceLimits } = require('worker_threads');
const { join } = require('path');

const hostname = '0.0.0.0';
const port = 3030;

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



const server = http.createServer((req, res) => {
  // main html
  
  
  
  res.statusCode = 200;
  res.setHeader('Content-Type', 'text/html');
  // if(!req.rawHeaders.includes('Referer')) {
    
  console.log('Request to '+req.url)


  if(req.url == '/') {
    fs.writeFile('./headshots.html', default_headshots_html, () => {
      console.log('Done wiping headshots.html')
    });

    fs.readFile('./index.html', function(err, data) {
      var sl_obj = new sl.Scrape_Learfield(done_scraping);
      var players = sl_obj.scrape(url_base, sport);

      res.write(data);
      res.end();
    });



  } else if(req.url == '/headshots') {
    var subpage = req.url.slice(1,req.url.length)
    var text = "Loading game " + subpage + "...";
    console.log('doing headshots?')
    fs.readFile('./headshots.html', function(err, data) {
      res.write(data);
      res.end();
    });



  } else {
    console.log('ignored request')
    res.end();
  }

});




server.listen(port, hostname, () => {
  console.log(`Server running at http://${hostname}:${port}/`);
});



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