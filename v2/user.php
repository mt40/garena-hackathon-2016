<?php
ini_set('default_charset', 'UTF-8');
header('Content-Type: text/html; charset=UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("utilities.php");
$utilities = new Utilities();
/** @var User $user */
$user = $utilities->getUser($_COOKIE["username"]);

?>
<!DOCTYPE html>
<html>

<head>
    <!--  Dont edit these-->
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="lib/css/font-awesome.min.css">
    <script src="lib/css/jquery-3.1.1.min.js"></script>

    <!--  Your style is in this file-->
    <link rel="stylesheet" type="text/css" href="user.css">
</head>

<body>
<!--  Top bar-->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="#">Profile <span class="sr-only">(current)</span></a>
            </li>
            <li>
                <a href="index.php">Book Dinner</a>
            </li>
            <li>
                <a href="" onclick="feelingLucky()">I'm feelin' lucky!</a>
            </li>
        </ul>
        <p class="navbar-text navbar-right">Signed in as <b><?php print $user->username; ?></b>&nbsp;&nbsp;</p>
    </div>
</nav>

<!--  Settings-->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6 text-center">
            <ul class="pagination">
                <li class="active"><a class="btn-page1" href="#">Individuality</a></li>
                <li><a class="btn-page2" href="#">Goals</a></li>
                <li><a class="btn-page3" href="#">Notification</a></li>
            </ul>
        </div>
    </div>

    <div class="container-fluid page-1">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h2>
                    What food ingredients are in your stop list?
                </h2>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-3">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            </div>
            <div class="col-sm-6">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="container-fluid">
                            <div class="col-sm-4">
                                <b>Regions</b>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="chinese" checked="checked">Chinese</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="japanese" checked="checked">Japanese</label>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <b>Carbonhydrates</b>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="rice" checked="checked">Rice</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="sugar" checked="checked">Sugar</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="potato" checked="checked">Potato</label>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <b>Others</b>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="mushrooms" checked="checked">Mushrooms</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="eggs" checked="checked">Eggs</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="chicken" checked="checked">Chicken</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="spicy" checked="checked">Spicy</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
            </div>
        </div>
    </div>
    <div class="container-fluid page-2" hidden>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">

            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            </div>

            <div class="col-sm-4">
                <h3 class="text-center">
                    What's your goal?
                </h3>
                <br/>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="checkbox">
                            <label><input type="checkbox" value="lose-weight">Lose weight</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="gain-weight">Gain weight</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="sustain-weight" checked="checked">Sustain
                                weight</label>
                        </div>
                    </div>
                </div>

                <h3 class="text-center">
                    Do you follow any diet?
                </h3>
                <br/>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="checkbox">
                            <label><input type="checkbox" value="protein">Protein</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="carbonhydrate">Carbonhydrate</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="vegetarian">Vegetarian</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
            </div>
        </div>
    </div>

    <br><br>
    <div class="container-fluid page-3" hidden>
        <div class="row">
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            </div>

            <div class="col-sm-4">
                <h3 class="text-center">
                    Would you like to be notified by email?
                </h3>
                <br/>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="checkbox">
                            <label><input type="checkbox" value="email-yes" checked="checked">YES</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="email-no">NO</label>
                        </div>
                    </div>
                </div>

                <h3 class="text-center">
                    How often you want to be notified?
                </h3>
                <br/>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="checkbox">
                            <label><input type="checkbox" value="email-once" checked="checked">Once</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="email-30">Every 30 minutes</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="email-60">Every 1 hour</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</div>

<script src="user.js"></script>
</body>

</html>
