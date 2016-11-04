<?php

class User
{
    public $username;
    public $user_foods = array();
    public $user_preferences = array();
    public $recommended_foods = array();

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function insertUserFood($userFood)
    {
        $this->user_foods[] = $userFood;
    }

    public function insertUserPreference($userPreference)
    {
        /** @var UserPreference $userPreference */
        if (!isset($userPreference->preference_type)) {
            $this->user_preferences[$userPreference->preference_type] = array();
        }
        $this->user_preferences[$userPreference->preference_type][] = $userPreference;
    }
}

class Food
{
    public $food_id;
    public $english_name;
    public $chinese_name;
    public $popularity;
    public $tags = array();

    public function __construct($food_id, $english_name, $chinese_name)
    {
        $this->food_id = $food_id;
        $this->english_name = $english_name;
        $this->chinese_name = $chinese_name;
    }

    public function insertTag($tag)
    {
        $this->tags[] = $tag;
    }

    public function foodHasTag($tag)
    {
        foreach ($this->tags as $food_tag) {
            if ($tag == $food_tag) {
                return true;
            }
        }
        return false;
    }
}

class UserFood
{
    public $food_id;
    public $date;
    public $username;
    public $rating;
    public $review;
    public $english_name;
    public $chinese_name;

    public function __construct($food_id, $date, $username, $rating, $review, $english_name, $chinese_name)
    {
        $this->food_id = $food_id;
        $this->date = $date;
        $this->username = $username;
        $this->rating = $rating;
        $this->review = $review;
        $this->english_name = $english_name;
        $this->chinese_name = $chinese_name;
    }
}

class UserPreference
{
    public $username;
    public $preference_type;
    public $preference_value;

    public function __construct($username, $preference_type, $preference_value)
    {
        $this->username = $username;
        $this->preference_type = $preference_type;
        $this->preference_value = $preference_value;
    }
}


class Utilities
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=food_flow", "root", "root",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
    }

    public function getAvailableFoods()
    {
        // TODO: Return array of food with number of current choices
    }

    public function getUserPastChoices($username)
    {
        // TODO: Return past choices for user
    }

    public function getUserCurrentChoice($username)
    {
        // TODO: Return user's current choice
    }

    public function userExists($username)
    {
        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($username));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (sizeof($resultSet) > 0) {
            return $username;
        } else {
            return null;
        }
    }

    public function getUser($username)
    {
        $user = new User($username);
        $sql = "SELECT username, user_food.food_id, rating, review,date ,english_name, chinese_name FROM user_food LEFT JOIN food ON user_food.food_id = food.food_id WHERE username = ?;";
        $paramArray = array($username);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultSet as $row) {
            $userFood = new UserFood($row["food_id"], $row["date"], $row["username"], $row["rating"], $row["review"], $row["english_name"], $row["chinese_name"]);
            $user->insertUserFood($userFood);
        }

        $sql = "SELECT username, preference_type, preference_value FROM user_preferences WHERE username = ?;";
        $paramArray = array($username);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultSet as $row) {
            $userPreference = new UserPreference($row["username"], $row["preference_type"], $row["preference_value"]);
            $user->insertUserPreference($userPreference);
        }
        return current($this->getRecommendedFood(array($user), 5));
    }

    public function replaceUserSettings($username, $userPreferencesContainer, $preferenceTypes)
    {
        $sql = "DELETE FROM user_preferences WHERE username = ? AND preference_type IN (1";
        foreach ($preferenceTypes as $type) {
            $sql .= ",?";
        }
        $sql .= ");";
        $paramArray = array_merge(array($username), $preferenceTypes);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);

        $sql = "INSERT INTO user_preferences VALUES (?,?,?);";
        $stmt = $this->pdo->prepare($sql);
        /** @var UserPreference $userPreference */
        foreach ($userPreferencesContainer as $userPreference) {
            $stmt->execute(array($username, $userPreference->preference_type, $userPreference->preference_value));
        }
    }

    public function replaceUserFilters($username, $userPreferencesContainer)
    {
        $sql = "DELETE FROM user_preferences WHERE username = ? AND preference_type IN (\"FILTER_RATING\",\"FILTER_TAG\",\"FILTER_POPULARITY\");";
        $paramArray = array($username);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);

        $sql = "INSERT INTO user_preferences VALUES (?,?,?);";
        $stmt = $this->pdo->prepare($sql);
        /** @var UserPreference $userPreference */
        foreach ($userPreferencesContainer as $userPreference) {
            $stmt->execute(array($username, $userPreference->preference_type, $userPreference->preference_value));
        }
    }

    public function getFoodDetails()
    {
        $sql = "SELECT food.food_id, english_name, chinese_name, tag, COALESCE(times_ordered,0) AS times_ordered FROM food LEFT JOIN food_tags on food.food_id = food_tags.food_id LEFT JOIN (SELECT food_id, COUNT(*) AS times_ordered FROM user_food WHERE date = ? GROUP BY food_id) a ON food.food_id = a.food_id ORDER BY times_ordered DESC;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(date("Y-m-d", time())));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $foodContainer = array();
        foreach ($resultSet as $row) {
            if (!isset($foodContainer[$row["food_id"]])) {
                $food = new Food($row["food_id"], $row["english_name"], $row["chinese_name"]);
                $foodContainer[$food->food_id] = $food;
            }
            $foodContainer[$row["food_id"]]->insertTag($row["tag"]);
            $foodContainer[$row["food_id"]]->popularity = $row["times_ordered"];
        }
        return $foodContainer;
    }

    public function generateEmailContents()
    {
        $sql = "SELECT a.username FROM users a LEFT JOIN user_food b ON a.username = b.username AND date = ? WHERE date IS NULL;";
        $paramArray = array(date("Y-m-d", time()));
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userContainer = array();
        foreach ($resultSet as $row) {
            $user = $this->getUser($row["username"]);
            $userContainer[$user->username] = $user;
        }
        $emailContent = array();
        /** @var User $user */
        foreach ($userContainer as $user) {
            $emailContent[$user->username] = array();
            foreach ($user->recommended_foods as $food) {
                $emailContent[$user->username][] = $food->english_name;
            }
        }
        return $emailContent;
    }

    public function getRecommendedFood($userContainer, $number)
    {
        $foodContainer = $this->getFoodDetails();
        /** @var User $user */
        foreach ($userContainer as $user) {
            $count = 0;
            /** @var Food $food */
            foreach ($foodContainer as $food) {
                $valid = True;
                foreach ($user->user_preferences as $preference_type => $user_preference) {
                    if ($preference_type == "AVOID") {
                        /** @var UserPreference $user_preference */
                        foreach ($user_preference as $avoidance) {
                            if ($food->foodHasTag($avoidance->preference_value)) {
                                $valid = False;
                            }
                        }
                    }
                }
                if ($valid && $count < $number) {
                    $user->recommended_foods[] = $food;
                    $count++;
                }
            }
        }
        return $userContainer;
    }

    public function userHasOrderedFoodToday($username)
    {
        $sql = "SELECT username, food_id FROM user_food WHERE username = ? AND date = ? ;";
        $paramArray = array($username, date("Y-m-d", time()));
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (sizeof($resultSet) > 0) {
            return $resultSet[0]["food_id"];
        } else {
            return false;
        }
    }

    public function removeTodayFoodOrder($username, $food_id)
    {
        $sql = "DELETE FROM user_food WHERE username = ? AND date = ? AND food_id = ?;";
        $paramArray = array($username, date("Y-m-d", time()), $food_id);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);
    }

    public function insertFoodOrder($username, $food_id)
    {
        $sql = "INSERT INTO user_food VALUES(?,?,?,NULL,NULL);";
        $paramArray = array(date("Y-m-d"),$username, $food_id);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramArray);
    }
}