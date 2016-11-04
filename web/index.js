"use strict";


function findItemByTags(tag) {
    let query = ":not(:has(.label:contains('" + tag + "')))";
    return $(".food-item").filter(query);
}

function toggleOpacityByTag(tag) {
    let target = findItemByTags(tag);
    let opacity = target.css("opacity");
    if (opacity < 1.0)
        target.fadeTo(0.5, 1);
    else
        target.fadeTo(0.5, 0.3);
}

function toggleTagSelection(tag_obj) {
    if (tag_obj.hasClass("label-default")) {
        tag_obj.removeClass('label-default').addClass('label-primary');
    }
    else {
        tag_obj.removeClass('label-primary').addClass('label-default');
    }
}

function toggleButtonOrder(btn) {
    if (btn.hasClass("btn-default")) {
        btn.removeClass('btn-default').addClass('btn-primary');
        btn.child(".btn-title").text("&nbsp;&nbsp;Ordered&nbsp;&nbsp;");
    }
    else {
        btn.removeClass('btn-primary').addClass('btn-default');
        btn.child(".btn-title").text("&nbsp;&nbsp;Order&nbsp;&nbsp;");
    }
}

function toggleItemSelection(username, food_id) {
    console.log(username, food_id);
    $.ajax({
        type: 'POST',
        url: "toggle-item.php",
        data: {
            "food_id": food_id,
            "username": username
        },
        success: function (data) {
            notifySuccess(data);
        }
    })
}

function notifySuccess(data) {
    var response = JSON.parse(data);
    if (response.action == "ordered") {
        alert("Successfully placed order");
    } else if (response.action == "removed") {
        alert("Order withdrawn");
    }
}

$(document).ready(function () {
    $(".filter-tag-list .label").click(function () {
        let tag = $(this).text();
        toggleTagSelection($(this));
        toggleOpacityByTag(tag);
    });

    $(".pulse-button").click(function () {
        $(".popup").show();
    });

    $(".popup").hide();
    $(".popup-close-btn").click(function () {
        $(".popup").hide();
    });

    /**
     * API request related code
     */
    $(".btn-order").click(function () {
        let food_id = $(this).closest(".food-item-id").text();
        var username = document.getElementById("username").innerHTML;
        toggleItemSelection(username, food_id);
        toggleButtonOrder($(this));

    });
});