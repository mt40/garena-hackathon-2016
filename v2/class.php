<?php

class User
{
    public $userID;
    public $userName;

    public function __construct($userID, $userName)
    {
        $this->userID = $userID;
        $this->userName = $userName;
    }
}

class Food
{
    public $foodID;
    public $foodName;
    public $foodLocalizations;
    public $foodAttributes;

    public function __construct($foodID, $foodName, $foodLocalizations, $foodAttributes)
    {
        $this->foodID = $foodID;
        $this->foodName = $foodName;
        $this->foodLocalizations = $foodLocalizations;
        $this->foodAttributes = $foodAttributes;
    }
}

class FoodSelection
{
    public $userID;
    public $foodID;
    public $selectionDate;
}

class Utilities
{
    public function getAvailableFoods()
    {
        // TODO: Return array of food with number of current choices
    }

    public function getUserPastChoices($userID)
    {
        // TODO: Return past choices for user
    }

    public function getUserCurrentChoice($userID)
    {
        // TODO: Return user's current choice
    }
}