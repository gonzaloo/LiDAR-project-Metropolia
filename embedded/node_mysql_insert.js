var mysql      = require('mysql');
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'joakim',
  password : 'mysqlpassword',
  database : 'joakim'
});
var deviceID = "1";
var date = "2015-10-19";
var time = "11:16:23";
var speed = "43.1";
var deviceID2 = "1";
var date2 = "2015-10-19";
var time2 = "11:17:23";
var speed2 = "43.2";
connection.connect();

var post  = [
  ['deviceID': deviceID, 'date': date, 'time': time, 'speed': speed],
  ['deviceID': deviceID2, 'date': date2, 'time': time2, 'speed': speed2],
];
console.log(post)
//post  = post + "," + {deviceID: deviceID, date: date, time: time, speed: speed};


connection.query('INSERT INTO `readings` VALUES ?', post, function(err, rows, fields) {
//  for (index = 0; index < rows.length; ++index) {
  //  console.log(rows[index].datetime + " " + rows[index].speed);

console.log(rows);
console.log(fields);
console.log("error: ")
console.log(err);

//}

  //console.log('The solution is: ', rows[0].solution);
});
connection.end();

// INSERT INTO readings (deviceID, date, time, speed) VALUES (?, ?, ?, ?)
