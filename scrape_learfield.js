const HTMLParser = require('node-html-parser');
const axios = require('axios'); 
const htmlelement = require('html-element');
const fetch = require('node-fetch');


const table_class_list = [
    'roster_jerseynum',
    'sidearm-table-player-name',
    'height',
    'rp_weight',
    'rp_position_short',
    'sidearm-table-custom-field rp_custom1',
    'roster_class'
]


class Player {
    constructor() {
        this.number = -1;
        this.fname = 'Fname';
        this.lname = 'Lastname';
        this.height_f = 'h_f';
        this.height_i = 'h_i';
        this.position = 'POS';
        this.bats = 'B';
        this.year = 'Yr.';
        this.url = 'none';
    }
}


class Scrape_Learfield {
    constructor(callback) {
        this.callback = callback;
    }
    
    
    scrape(base_url, sport) {
        // this.base_url = base_url;

        var url = base_url+'/sports/'+sport+'/roster';
        var root;
        console.log('Accessing '+ url + '...');
        // axios.get(url)
        // .then(res => {
        //     handle_result(res);
        // })
        // .catch(error => {
        //     console.error(error);
        // })
        fetch(`${url}`)
        .then (res => res.text())
        .then (body => root = this.handle_roster_result(body, base_url))
        // .then (() => extractData(root))
        return
    }


    handle_roster_result(res, base_url) {
        var root = HTMLParser.parse(res);
        var page_type = 1;
        var subelement;
        var player;
        var player_list = [];

        // roster view
        if(page_type == 1) {
            var elements = root.getElementsByTagName('tr');
            for(let i=0; i < elements.length; i++) { 
            // for(let i=0; i < 5; i++) {    
                // if(elements[i].rawAttrs.includes('')):
                //     console.log(elements[0]);
                for(let j=0; j < elements[i].childNodes.length; j++){
                    subelement = elements[i].childNodes[j];
                    if(subelement.constructor.name == 'HTMLElement') {
                        if(subelement.rawAttrs.includes('class')){
                            // console.log(subelement)
                            if(subelement.rawAttrs.includes(table_class_list[0])) {
                                player = new Player();
                                // console.log(subelement.childNodes[0]._rawText)
                                player.number = subelement.childNodes[0]._rawText;
                            }
                            if(subelement.rawAttrs.includes(table_class_list[1])) {
                                // console.log(subelement.childNodes[1].rawAttrs)
                                var raw_attr_with_url = subelement.childNodes[1].rawAttrs;
                                var split_em = raw_attr_with_url.split('"');
                                var url = base_url + split_em[1];
                                player.url = url;
                                var names = subelement.childNodes[1].rawText
                                player.fname = names.split(' ')[0]
                                player.lname = names.slice(player.fname.length+1)   
                                // console.log(player.fname + ',' + player.lname)                         
                            }
                            if(subelement.rawAttrs.includes(table_class_list[2])) {
                                var vals = subelement.childNodes[0]._rawText.split('-');
                                player.height_f = vals[0];
                                player.height_i = vals[1];
                            }
                            if(subelement.rawAttrs.includes(table_class_list[4])) {
                                player.position = subelement.childNodes[0]._rawText;
                            }
                            if(subelement.rawAttrs.includes(table_class_list[5])) {
                                player.bats = subelement.childNodes[0].childNodes[0].rawText.slice(0,1);
                                // console.log(subelement.childNodes[0].childNodes[0].rawText.slice(0,1))
                            }
                            if(subelement.rawAttrs.includes(table_class_list[6])) {
                                player.year = subelement.childNodes[0]._rawText;
                                player_list.push(player);
                            }
                            
                        }
                    }
                }
            }
            
            this.create_player(player_list);
            // console.log('\nNEW REQUEST #######################################################################\n')
        }
    }



    async create_player(player_list) {
        var headshot_urls = [];

        for(let j=0; j<player_list.length; j++) {
            var response = await fetch(`${player_list[j].url}`);
            var body = await response.text();
            var root = HTMLParser.parse(body);
            var elements = root.getElementsByTagName('img');
            
            for(let i=0; i < elements.length; i++) {    
                if(elements[i].rawAttrs.includes('net/images/2022')) {
                    var url = elements[i].rawAttrs.split('"')[1].split('?')[0];
                }
            }
            console.log(url)
            headshot_urls.push(url)
        }

        this.callback(headshot_urls, player_list)
    }
}


module.exports = {Scrape_Learfield};