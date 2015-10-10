//required things for stuff.
// chance creates random dates, request is what we use for http calls
var Chance = require('chance');
var chance = new Chance();
var request = require('request');

//ingest base url
var baseurl = "http://lidar.educloud.metropolia.fi/~joakim/receive.php?";
var entries = process.argv[2];
// how many entries to generate
if (typeof process.argv[2] != 'undefined') {

} else {
  var entries = 1000;
}
//how many entries were asked for (the entries amount will change due to duplicates)
var requested = entries;
// which device is it
var deviceID = 1;
// make the whole url
var url = baseurl + "deviceID=" + deviceID;

function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}

function fakespeed() {
  var floatspeed = (Math.random() * 100); // generate something
  var speed = floatspeed.toFixed(1); // dem decimals, yo
  // console.log(speed); // ayy lets print dat fake speed
  return speed;
}

function timestampNow() {
  //var today = new Date();
  var today = chance.birthday();
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
var dupe = 0;
var jsonarray = {};
for(i=0; i<entries; i++){
var timestamp = timestampNow();
var speed = fakespeed();
/* console.log(timestamp);
console.log(speed);
console.log(i); */
if (typeof jsonarray[timestamp] != 'undefined') {
  dupe++;
  entries++;
}

jsonarray[timestamp] = speed;

}
var jsonstring = JSON.stringify(jsonarray);
var actual = entries - dupe;
//console.log("JSON: " + jsonstring);

request.post(
    url,
    { form: { json: jsonstring } },
    function (error, response, body) {
      console.log("Error: " + error);
    //  console.log(response);
        if (!error && response.statusCode == 200) {
            console.log(body);
            console.log("Entries: " + entries);
            console.log("Dupes: " + dupe);
            console.log("Actual entries: " + actual);
            console.log("Requested entries: " + requested);
            console.log("URL: " + url);

        }
    }
);
