I use php version -PHP 8.1.7 and  httpie.

//get all participants
http get localhost:80/index.php/participant
//get participant with id 1 
http get localhost:80/index.php/participant/1
//create new participant
http post localhost:80/index.php/participant name="Ivan" country="Bulgaria" birth_date="1996-10-03" position="defender"
//delete participant with id 1 
http DELETE localhost:80/index.php/participant/1
//update participant with id 8  name 
http patch localhost:80/index.php/participant/8 name="CustomNewName"