#!/usr/bin/env node

  "use strict";
//require cylon
var Cylon = require("cylon");
//require system so we can run commands
var sys = require("sys");
var exec = require("child_process").exec;
var child;

Cylon.robot({
  connections: {
    edison: { adaptor: "intel-iot" }
  },

  devices: {
    sensor: { driver: "analog-sensor", pin: 0, lowerLimit: 650, upperLimit: 990 }

  },
  work: function(my) {
    var analogValue = 0;
    var voltage = 0;
    var undervoltage = 0;
    var overvoltage = 0;
    every((1).second(), function() {
      analogValue = my.sensor.analogRead();
      voltage = analogValue * 0.0146; // 0.0146 comes from 15/1027 (yes, not 1024)
      console.log('Analog value => ', voltage);
    });

    my.sensor.on('lowerLimit', function(val) {
      undervoltage++;
      voltage = val * 0.0146;
      console.log('Undervoltage! ', undervoltage);
      console.log('Voltage: ', voltage);
        // if we get at least 20 undervoltage readings, shut down!
      if (undervoltage > 20) { child = exec("echo shutdown -h now", function(error, stdout, stderr) {
        sys.print('stdout: ' + stdout);
        sys.print('stderr: ' + stderr);
        if (error !== null) { console.log('exec error: ' + error); }

        //we need to stop what we're doing, we're running out of power
          process.exit(1);
        }
        );
      }
    });

    my.sensor.on('upperLimit', function(val) {
      highvoltage++;
      voltage = val * 0.0146;
      console.log('Overvoltage!', highvoltage);
      console.log('Voltage: ', voltage);
    });
  }
}).start();

