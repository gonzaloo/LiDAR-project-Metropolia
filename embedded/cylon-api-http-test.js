"use strict";

var Cylon = require("cylon");

Cylon.api("http", {
  ssl: false // serve unsecured, over HTTP

  // optional configuration here.
  // for details see 'Configuration' section.
});

Cylon.robot({
  name: "LIDAR",

  connections: {
    keyboard: { adaptor: 'keyboard' }
  },

  devices: {
    keyboard: { driver: 'keyboard' }
  },

  work: function() {
    // we'll interact with this robot through the API
  }
}).start();
