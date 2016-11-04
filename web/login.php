<?php

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

if (isset($_POST["username"])) {
    $loggedIn = False;
    $username = $_POST["username"];
    $password = $_POST["password"];
    $client = ClientBuilder::create()
        ->addConnection('default', 'http://neo4j:123456@localhost:7474')
        ->build();

    $query = "MATCH (u:User) WHERE u.username = \"$username\" RETURN u.username";
    $result = $client->run($query);
    foreach ($result->getRecords() as $record) {
        if ($record->value('u.username') == $username) {
            $loggedIn = True;
        }
    }

    if (!$loggedIn) {
        setcookie("username", $username);
        header("Location: index.php");
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="lib/css/font-awesome.min.css">
    <link rel="stylesheet" href="login.css">
    <script src="lib/css/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<?php if (isset($loggedIn)): ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><strong>Error</strong> - Username not found</p>
    </div>
<?php endif; ?>
<body>
<div class="container-fluid login-container well">
    <form method="post">
        <div>

            Username:
            <input type="text" class="form-control" placeholder="Username" aria- describedby="basic-addon1"
                   id="username" name="username" <?php isset($loggedIn) ? print "value=\"$username \"" : print ""; ?>>
            <br/>
            Password:
            <input type="password" class="form-control" placeholder="Password" aria- describedby="basic-addon1"
                   id="password" name="password">
        </div>
        <br/>
        <div class="pull-right">
            <input type="submit" class="btn btn-primary btn-login" type="button" value="Login">
        </div>
    </form>
</div>

<!--<script src="assets/data/user-data.js"></script>-->
<!--<script src="lib/js/js-cookie.js"></script>-->
<!--<script src="login.js"></script>-->
</body>

</html>