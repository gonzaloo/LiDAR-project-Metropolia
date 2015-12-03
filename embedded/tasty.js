#!/usr/bin/node

"use strict";
var ThingSpeakClient = require('thingspeakclient');
var client = new ThingSpeakClient();
var client = new ThingSpeakClient({useTimeoutMode:false});
var client = new ThingSpeakClient({updateTimeout:20000});
client.attachChannel(69542, { writeKey:'LPT6V6HXQOXDFTCC'});
var fs = require('fs');
var colors = require('colors');
var Cylon = require("cylon");
var previousdistance = 0;
var previoustime = Date.now();
var speed_no_fix = 0;
var speed = 0;
var speed2 = 0;
var change = 0;
var elapsedtime = Date.now();
var now = Date.now();
var br = String.fromCharCode(9);
Cylon.robot({
  connections: {
    edison: { adaptor: "intel-iot" }
  },

  devices: {
    lidar: { driver: "lidar-lite" }
  },

  work: function(my) {
//    every(500, function () {
//      console.log("velocity: " + speed);
//    });
        console.log("Distance       Time             Velocity \n")
    every(100, function() {
      my.lidar.distance(function(err, data) {
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
                if(change < 100){
                        console.log(change + br + br  + elapsedtime + br + br + speed +" km/h");
                        client.updateChannel(69542, {field1: speed});
                }
                else{
                        fs.appendFile('log.txt', change + "\n" ,function(err){
                                     if (err) throw err;
                        });

                console.log(colors.red(change) + br + br  + elapsedtime + br + br + speed +" km/h");
                }
//              console.log("uncorrected: " + speed2);
        }
        }

        previoustime = now;
        previousdistance = data;
      });
    });
  }
}).start();


