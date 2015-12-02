#!/usr/bin/node

"use strict";
var fs = require('fs');
var colors = require('colors');
var Cylon = require("cylon");
var request = require('request');
var baseurl = "http://lidar.educloud.metropolia.fi/~joakim/receive.php?";
var entries = 0;
var previousdistance = 0;
var previoustime = Date.now();
var speed_no_fix = 0;
var speed = 0;
var speed2 = 0;
var change = 0;
var elapsedtime = Date.now();
var now = Date.now();
var br = String.fromCharCode(9);
var jsonarray = {};
// which device is it
var deviceID = 1;
// make the whole url
var url = baseurl + "deviceID=" + deviceID;

function timestampNow() {
  var today = new Date();
  //var today = chance.birthday();
  var dd = zeroPad(today.getDate(), 2); // padding because i don't want single digits
  var mo = zeroPad(today.getMonth()+1, 2); //january is 0? why
  var hh = zeroPad(today.getHours(), 2);
  var mm = zeroPad(today.getMinutes(), 2);
  var ss = zeroPad(today.getSeconds(), 2);
  var yyyy = today.getFullYear();
  var string = "" // need to make a string to concatenate a string
  var timestamp = string.concat(yyyy,"",mo,"",dd,"",hh,"",mm,"",ss); //no idea if those ""'s are needed, but i added them anyway. hu-ah!
// console.log(timestamp);
  return timestamp;
}

function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}

Cylon.robot({
  connections: {
    edison: { adaptor: "intel-iot" }
  },

  devices: {
    lidar: { driver: "lidar-lite" }
  },

  work: function(my) {
    every(10000, function () { //every 10 seconds, send shit
	var jsonstring = JSON.stringify(jsonarray);
	// SEND SHIT
	
		request.post(
		    url,
		    { form: { json: jsonstring } },
		    function (error, response, body) {
		      console.log("Error: " + error);
	//	      console.log(response);
		        if (!error && response.statusCode == 200) {
		            console.log(body);
	//	            console.log("Entries: " + entries);
	//	            console.log("Dupes: " + dupe);
	//	            console.log("Actual entries: " + actual);
	//	            console.log("Requested entries: " + requested);
	//	            console.log("URL: " + url);
			jsonarray = {}; // empty it if shit is sent
		
		        }
		    }
	);
	//console.log("sent: " + entries);
    });
        console.log("Distance       Time             Velocity \n") // runs once
    every(65, function() {
      my.lidar.distance(function(err, data) {
	if (data < 40000) { //if the distance is over 40 meters, ignore. this is probably the sun or a reflection.
	        now = Date.now();
	        elapsedtime = now - previoustime;
	//        console.log("distance: " + data);
	        if (data < previousdistance) {
	                change = (previousdistance - data);
	//              console.log("difference: " + change);
	                if (change > 5){
	//              speed_no_fix = change * 3.6 * (elapsedtime/1000);
	                speed_no_fix = (change/elapsedtime) * 36;
			speed = speed_no_fix.toFixed(2)
	//              speed2 = change * 0.36;
	//              console.log("time: " + elapsedtime);
	//              console.log("velocity: " + speed + " km/h");
	                if(change < 20){ // if change less than 20, don't log it, 20 cm doesn't matter
				console.log(change + br + br  + elapsedtime + br + br + speed +" km/h");
			//	console.log(".");
	                }
	                else{
	                	fs.appendFile('log.txt', change + "\n" ,function(err){
	                        	if (err) throw err;
	                	});
				var timestamp = timestampNow();
				if (typeof jsonarray[timestamp] != 'undefined') { //checking if the speed for this second already exists
					// If the speed for this second already exists, only replace it when the current measurement shows a higher speed		
					if(parseInt(jsonarray[timestamp],10) < parseInt(speed,10)) {  //remember to make your strings integers, kids, or otherwise your comparisons will fail <3
					//	console.log("typeof jsonarraytimestamp " + typeof jsonarray[timestamp] + " typeof speed " + typeof speed); //turns out, a number is indeed a string unless you make it one.
					//	console.log(timestamp + " already existed, measured speed " + speed + " was higher than old speed " + jsonarray[timestamp]);
						jsonarray[timestamp] = speed;
					};
				}
				else { jsonarray[timestamp] = speed; console.log(timestamp +  "didn't exist, adding speed " + speed); } // otherwise, just add this speed.
				//entries++;
				console.log(colors.red(change) + br + br  + elapsedtime + br + br + speed +" km/h");
	                }
	//              console.log("uncorrected: " + speed2);
	        }
	        }
	
	        previoustime = now;
	        previousdistance = data;
	}
	else { console.log("Invalid distance: " +data); }
      });
    });
  }
}).start();

