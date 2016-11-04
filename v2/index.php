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
$foodContainer = $utilities->getFoodDetails();

$selected_food_id = NULL;
/** @var UserFood $userFood */
foreach ($user->user_foods as $userFood) {
    if ($userFood->date == date("Y-m-d")) {
        $selected_food_id = $userFood->food_id;
    }
}
$tagContainer = array();
/** @var Food $food */
foreach ($foodContainer as $food) {
    foreach ($food->tags as $tag) {
        $tagContainer[$tag] = $tag;
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
                <?php foreach ($tagContainer as $tag): ?>
                    <a class="label label-default tag"><?php print $tag; ?></a>
                <?php endforeach; ?>
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
        <?php foreach ($foodContainer as $food):
            /** @var Food $food */ ?>
            <div class="col-sm-3">
                <div class="panel panel-default food-item">
                    <div class="panel-body">
                        <i class="thumbnail">
                            <img class="food-image" src="food_image/<?php print $food->food_id; ?>.PNG"/>
                        </i>

                        <div class="caption">
                            <h5><?php print $food->english_name; ?></h5>
                            <p>
                                <?php print $food->chinese_name; ?>
                            </p>
                            <div class="food-label-list">
                                <?php foreach ($food->tags as $tag): ?>
                                    <span class="label label-default tag"><?php print $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <br/>
                            <div class="hidden food-item-id"><?php print $food->food_id; ?></div>
                            <button type="button"
                                    class="btn <?php $selected_food_id == $food->food_id ? print "btn-primary" : print "btn-default"; ?> btn-order"
                                    aria-label="Left Align"
                                    onclick="toggleFoodOrder(this,<?php print $food->food_id; ?>)"
                                    id="button-<?php print $food->food_id; ?>">
                                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                                <span class="btn-title">&nbsp;&nbsp;Order&nbsp;&nbsp;</span>
                                <span class="badge"
                                      id="badge-<?php print $food->food_id; ?>"><?php print $food->popularity; ?></span>
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
<!--<div class="popup">-->
<!--    <div class="popup-mask"></div>-->
<!--    <button type="button" class="btn btn-default popup-close-btn">-->
<!--        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>-->
<!--        Close-->
<!--    </button>-->
<!--    <div class="popup-content" id="network"></div>-->
<!--</div>-->
<script src="index.js"></script>
<!--<script src="http://d3js.org/d3.v2.min.js?2.9.6"></script>-->
<!--<script>-->
<!--    var width = 2000,-->
<!--        height = 1000,-->
<!--        radius = 10;-->
<!---->
<!--    var color = d3.scale.category20();-->
<!---->
<!--    var force = d3.layout.force()-->
<!--        .gravity(0.05)-->
<!--        .charge(-100)-->
<!--        .linkDistance(100)-->
<!--        .size([width *= 2 / 3, height *= 2 / 3]);-->
<!---->
<!--    var svg = d3.select("#network").append("svg")-->
<!--        //        .attr("width", width)-->
<!--        .attr('width', '100%')-->
<!--        .attr("height", '100%');-->
<!---->
<!--    d3.json("json.php", function (graph) {-->
<!--        force-->
<!--            .nodes(graph.nodes)-->
<!--            .links(graph.links)-->
<!--            .start();-->
<!---->
<!--        var link = svg.selectAll(".link")-->
<!--            .data(graph.links)-->
<!--            .enter().append("line")-->
<!--            .attr("class", "link")-->
<!--            .style("stroke-width", function (d) {-->
<!--                return Math.sqrt(d.value);-->
<!--            });-->
<!---->
<!--        var gnodes = svg.selectAll('g.gnode')-->
<!--            .data(graph.nodes)-->
<!--            .enter()-->
<!--            .append('g')-->
<!--            .classed('gnode', true);-->
<!---->
<!--        var node = gnodes.append("circle")-->
<!--            .attr("class", "node")-->
<!--            .attr("r", radius)-->
<!--            .style("fill", function (d) {-->
<!--                return color(d.group);-->
<!--            })-->
<!--            .call(force.drag);-->
<!---->
<!--        var labels = gnodes.append("text")-->
<!--            .text(function (d) {-->
<!--                return d.name;-->
<!--            });-->
<!---->
<!--        console.log(labels);-->
<!---->
<!--        force-->
<!--            .nodes(graph.nodes)-->
<!--            .links(graph.links)-->
<!--            .on("tick", tick)-->
<!--            .start();-->
<!---->
<!--        function tick() {-->
<!--            link.attr("x1", function (d) {-->
<!--                    return d.source.x;-->
<!--                })-->
<!--                .attr("y1", function (d) {-->
<!--                    return d.source.y;-->
<!--                })-->
<!--                .attr("x2", function (d) {-->
<!--                    return d.target.x;-->
<!--                })-->
<!--                .attr("y2", function (d) {-->
<!--                    return d.target.y;-->
<!--                });-->
<!--            gnodes.attr("transform", function (d) {-->
<!--                return 'translate(' + [Math.max(radius, Math.min(width - radius, d.x)), Math.max(radius, Math.min(height - radius, d.y))] + ')';-->
<!--            });-->
<!--        }-->
<!--    });-->
<!---->
<!--</script>-->
</body>

</html>