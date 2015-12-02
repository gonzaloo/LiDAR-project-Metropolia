var notp = require('notp');

//.... some initial login code, that receives the user details and TOTP / HOTP token

var key = 'oursecretpassword';
var token = notp.totp.gen(key);
// Check TOTP is correct (HOTP if hotp pass type)
var login = notp.totp.verify(token, key);
console.log(key);
console.log(token);
console.log(login);

// invalid token if login is null
if (!login) {
    return console.log('Token invalid');
}

// valid token
console.log('Token valid, sync value is %s', login.delta);
