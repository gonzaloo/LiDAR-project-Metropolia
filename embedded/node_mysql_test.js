var mysql      = require('mysql');
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'joakim',
  password : 'mysqlpassword',
  database : 'joakim'
});

connection.connect();

connection.query('SELECT  `speed`, `time`, `date`, concat(date, " " , time) AS `datetime` FROM  `readings` ORDER BY `date` ASC, `time` ASC', function(err, rows, fields) {
  for (index = 0; index < rows.length; ++index) {
    console.log(rows[index].datetime + " " + rows[index].speed);

}

  //console.log('The solution is: ', rows[0].solution);
});
connection.end();
