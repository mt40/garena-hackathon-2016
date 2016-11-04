"use strict";

function findItemByTags(tag) {
  let query = ":has(.label:contains('" + tag + "'))";
  return $(".food-item").filter(query);
}

function toggleOpacityByTag(tag) {
  let target = findItemByTags(tag);
  let opacity = target.css("opacity");
  if(opacity < 1.0)
    target.fadeTo(0.5, 1);
  else
    target.fadeTo(0.5, 0.3);
}

$(document).ready(function(){
    $(".filter-tag-list .label").click(function(){
      let tag = $(this).text();
      toggleOpacityByTag(tag);
    });
});