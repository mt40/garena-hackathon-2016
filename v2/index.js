"use strict";

function findItemContainTags(tags) {
    let query = "";
    tags.each(function (index) {
        query += ":has(.label:contains('" + $(this).text().trim() + "'))";
        if (index < tags.length - 1)
            query += ",";

    });
    console.log(query);
    return $(".food-item").filter(query);
}

function showFoodItemByTags(tags) {
    let target = findItemContainTags(tags);
    target.fadeTo(0.5, 1);
}

function hideAllFoodItems() {
    $(".food-item").fadeTo(0.5, 0.3);
}

function showAllFoodItems() {
    $(".food-item").fadeTo(0.5, 1);
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
    console.log(data);
    var response = JSON.parse(data);
    alert(response.action);
    if (response.status == "Success" || response.status == "Removed") {
        var item_id = response.food_id;
        document.getElementById("badge-" + parseInt(item_id)).innerHTML = response.new_number;
        if (response.status == "Success") {
            document.getElementById("button-" + parseInt(item_id)).className = "btn btn-primary btn-order"
        } else {
            document.getElementById("button-" + parseInt(item_id)).className = "btn btn-default btn-order"
        }
    }

}

function getSelectedTags() {
    return $(".filter-tag-list .label-primary");
}

function notify() {
    if (!Notification) {
        console.log('Desktop notifications not available in your browser. Try Chromium.');
        return;
    }
    var notification = new Notification('Notification title', {
        icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
        body: "Hey there! You've been notified!",
    });

    notification.onclick = function () {
        window.open("http://stackoverflow.com/a/13328397/1269037");
    };
}

$(document).ready(function () {
    $(".filter-tag-list .label").click(function () {
        //notify();
        hideAllFoodItems();

        // Ensure the items that are selected by tags
        // are visible
        toggleTagSelection($(this));
        let tags = getSelectedTags();
        console.log(tags);
        if (tags.length == 0 || findItemContainTags(tags).length == 0) {
            // nothing is selected, show all
            showAllFoodItems();
        }
        else {
            showFoodItemByTags(tags);
        }
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
    //$(".btn-order").click(function () {
    //    //let food_id = $(this).closest(".food-item-id").text()
    //    //console.log(food_id);
    //    //var username = document.getElementById("username").innerHTML;
    //    //toggleItemSelection(username, food_id);
    //    toggleButtonOrder($(this));
    //
    //});

    // Setup Chrome notification
    if (Notification.permission !== "granted")
        Notification.requestPermission();
});

function toggleFoodOrder(btn, food_id) {
    var username = document.getElementById("username").innerHTML;
    toggleItemSelection(username, food_id);
}

function feelingLucky() {
    $.ajax({
        type: 'POST',
        url: "feeling-lucky.php",
        data: {
            "action": "lucky"
        },
        success: function (data) {
            console.log(data);
            alert("You have ordered a mysterious food for yourself. Proceed to the pantry to find out what it is!");
            //notifySuccess(data);
        }
    })
}