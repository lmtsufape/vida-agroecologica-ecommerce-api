var admin = require("firebase-admin");

var serviceAccount = require("/home/lmts/Downloads/teste-bonito-firebase-adminsdk-b7psy-197761ee8c.json");

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount)
});
