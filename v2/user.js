"use strict";

function showPage(page_index) {
    $(".container-fluid.page-" + page_index).show();
    $(".btn-page" + page_index).closest("li").addClass("active");
}

function hidePage(page_index) {
    $(".container-fluid.page-" + page_index).hide();
    $(".btn-page" + page_index).closest("li").removeClass("active");
}

function getCheckedValues(page_index) {
    let checked = [];
    $(".container-fluid.page-" + page_index + " div.checkbox input:checked").each(function (i) {
        let value = $(this).val();
        if (value) {
            checked.push(value);
        }
    });
    return checked;
}

function collectInfo() {
    let info = {
        "individuality": getCheckedValues(1),
        "goals": getCheckedValues(2),
        "notification": getCheckedValues(3)
    };
    console.log(info);
    return info;
}

function sendRequest(url, obj) {
    console.log(obj);
    $.ajax(url, {
        type: 'POST',
        data: {user_data: obj}
    }).done(function (data) {
        console.log(data);
        console.log("Request sent to url.", obj);
    });
}

$(document).ready(function () {
    $(".btn-page1").click(function () {
        showPage(1);
        hidePage(2);
        hidePage(3);
        var url = "preferences.php";
        sendRequest(url, collectInfo());
    });

    $(".btn-page2").click(function () {
        hidePage(1);
        showPage(2);
        hidePage(3);
        var url = "preferences.php";
        sendRequest(url, collectInfo());
    });

    $(".btn-page3").click(function () {
        hidePage(1);
        hidePage(2);
        showPage(3);
        var url = "preferences.php";
        sendRequest(url, collectInfo());
    });
});