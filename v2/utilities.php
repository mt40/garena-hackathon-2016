<?php

class User
{
    public $username;
    public $user_foods = array();
    public $user_preferences = array();

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

    public function __construct($food_id, $english_name, $chinese_name)
    {
        $this->food_id = $food_id;
        $this->english_name = $english_name;
        $this->chinese_name = $chinese_name;
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
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=food_flow", "root", "root");
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
        return $user;
    }
}