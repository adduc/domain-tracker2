; <?php exit(); __halt_compiler();
; Security measure above taken from
; http://www.php.net/manual/en/function.parse-ini-file.php#99036

[paths]
root = "disk location"
url = "url to app root"

[errors]
display = true
level = -1
verbose = true

[pdo]
dsn = "mysql:host=localhost;dbname=database"
username = "username"
password = "password"
