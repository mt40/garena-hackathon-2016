var Chance = require('chance');
var fs = require('fs-extra');

// Instantiate Chance so it can be used
var chance = new Chance();

/**
 * User data schema:
 * - name: string
 * - food-choice: int (food id) 
 */
let SIZE = 10;
let food_id_min = 1, food_id_max = 10;
let data = ["name,food-choice"];

console.log("Generating...");

for(let i = 0; i < SIZE; ++i) {
  let name = chance.name();
  let food_choice = chance.integer({min: food_id_min, max: food_id_max});
  data.push(name + ',' + food_choice);
}

let file_content = data.join("\n");

console.log("Writing to csv...");
fs.outputFileSync("out/user-data.csv", file_content);

console.log("Complete!");
