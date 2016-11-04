<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../class.php";
require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

if (!isset($_COOKIE["username"])) {
    header("Location: login.php");
}

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
    ->build();

$query = "MATCH (f:Food) RETURN f.id,f.english_name, f.chinese_name, f.tag";
$result = $client->run($query);
$foodArray = array();
foreach ($result->getRecords() as $record) {
    $foodArray[] = new Food($record->value('f.id'), $record->value('f.english_name'), $record->value('f.chinese_name'), $record->value('f.tag'));
}
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="lib/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="pulse-button.css">
    <link rel="stylesheet" type="text/css" href="layout.css">
    <link rel="stylesheet" type="text/css" href="popup.css">
    <script src="lib/css/jquery-3.1.1.min.js"></script>
</head>

<body>
<div class="top-cover well"><h1 class="cover-title">Dinner Addicts</h1></div>

<!-- Tag lists -->
<div class="hidden" id="username"><?php print $_COOKIE["username"]; ?></div>
<div class="container-fluid filter-tag-list">
    <div class="row">
        <div class="col-sm-10">
            <h3 class="text-gray"># tags</h3>
            <div class="tag-list-region">
                <a class="label label-default">Chinese</a>
                <a class="label label-default">Indonesian</a>
                <a class="label label-default">Japanese</a>
                <a class="label label-default">Korean</a>
                <a class="label label-default">Thai</a>
                <a class="label label-default">Western</a>
                <a class="label label-default">Local Items</a>
            </div>
            <div>
                <a class="label label-default">spicy</a>
                <a class="label label-default">sour</a>
            </div>
            <div>
                <a class="label label-default">vegetarian</a>
                <a class="label label-default">Halal</a>
                <a class="label label-default">beef</a>
                <a class="label label-default">pork</a>
                <a class="label label-default">chicken</a>
                <a class="label label-default">fish</a>
            </div>
            <div>
                <a class="label label-default">rice</a>
                <a class="label label-default">noodle</a>
                <a class="label label-default">bun</a>
                <a class="label label-default">soup</a>
                <a class="label label-default">salad</a>

            </div>
        </div>

        <div class="col-sm-2">
            <button class="pulse-button pull-right">
          <span>
            <h4><i class="fa fa-share-alt" aria-hidden="true"></i> Explore</h4>
          </span>
            </button>
        </div>
    </div>
</div>

<!-- List of food items -->
<div class="food-grid">
    <div class="container-fluid">
        <!--      <div class="row">-->
        <?php foreach ($foodArray as $food):
            /** @var Food $food */ ?>
            <div class="col-sm-4 col-md-2">
                <div class="panel panel-default food-item">
                    <div class="panel-body">
                        <i class="thumbnail">
                            <img src="assets/images/food1.jpg"/>
                        </i>

                        <div class="caption">
                            <h5><?php print $food->foodName; ?></h5>
                            <p>
                                <?php print $food->foodLocalizations; ?>
                            </p>
<!--                            <p class="food-desc">-->
<!--                                --><?php //print $food->foodLocalizations; ?>
<!--                            </p>-->
                            <div class="food-label-list">
                                <span class="label label-default"><?php print $food->foodAttributes;?></span>
                            </div>
                            <br/>
                            <button type="button" class="btn btn-default btn-order" aria-label="Left Align">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                                <span class="btn-title">&nbsp;&nbsp;Order&nbsp;&nbsp;</span>
                                <span class="badge"><?php print rand(1, 10); ?></span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!--    </div>-->
</div>
<div class="container-fluid">
    <a href="logout.php" class="btn btn-info pull-right">Logout</a>
</div>
<br>

<!--  Popup -->
<div class="popup">
    <div class="popup-mask"></div>
    <button type="button" class="btn btn-default popup-close-btn">
        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        Close
    </button>
    <div class="popup-content" id="network"></div>
</div>
<script src="index.js"></script>
<script src="http://d3js.org/d3.v2.min.js?2.9.6"></script>
<script>
    var width = 2000,
        height = 1000,
        radius = 10;

    var color = d3.scale.category20();

    var force = d3.layout.force()
        .gravity(0.05)
        .charge(-100)
        .linkDistance(100)
        .size([width *= 2 / 3, height *= 2 / 3]);

    var svg = d3.select("#network").append("svg")
        //        .attr("width", width)
        .attr('width', '100%')
        .attr("height", '100%');

    d3.json("json.php", function (graph) {
        force
            .nodes(graph.nodes)
            .links(graph.links)
            .start();

        var link = svg.selectAll(".link")
            .data(graph.links)
            .enter().append("line")
            .attr("class", "link")
            .style("stroke-width", function (d) {
                return Math.sqrt(d.value);
            });

        var gnodes = svg.selectAll('g.gnode')
            .data(graph.nodes)
            .enter()
            .append('g')
            .classed('gnode', true);

        var node = gnodes.append("circle")
            .attr("class", "node")
            .attr("r", radius)
            .style("fill", function (d) {
                return color(d.group);
            })
            .call(force.drag);

        var labels = gnodes.append("text")
            .text(function (d) {
                return d.name;
            });

        console.log(labels);

        force
            .nodes(graph.nodes)
            .links(graph.links)
            .on("tick", tick)
            .start();

        function tick() {
            link.attr("x1", function (d) {
                    return d.source.x;
                })
                .attr("y1", function (d) {
                    return d.source.y;
                })
                .attr("x2", function (d) {
                    return d.target.x;
                })
                .attr("y2", function (d) {
                    return d.target.y;
                });
            gnodes.attr("transform", function (d) {
                return 'translate(' + [Math.max(radius, Math.min(width - radius, d.x)), Math.max(radius, Math.min(height - radius, d.y))] + ')';
            });
        }
    });

</script>
</body>

</html>