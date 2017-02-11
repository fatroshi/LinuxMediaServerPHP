<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 02/10/16
 * Time: 22:43
 */


include_once ("Database.php");
include_once("Media.php");
include_once ("Player.php");
include_once ("Category.php");

class Controller {

    private $database;
    private $category;
    private $media;
    private $player;

    function __construct() {
        $this->database = new Database();                                       // Connect to the database
        $this->category = new Category($this->database);
    }

    public function updateItem($query){
        $sql = "UPDATE Items SET {$query}";
        $conn = $this->database->getConnection();

        if($conn->query($sql) === true){
            return true;
        }else{
            echo "Error: " . $sql . "<br>" . $conn->error;
            return false;
        }
    }


    public function getCategoryItems($categoryId){
        return $this->category->getCategoryItems($categoryId);
    }

    public function getAllItems(){
        return $this->category->getAllItems();
    }

    /**
     * Get logged in user
     */
    public function getUser(){
        $this->user->getUser();
    }


    /**
     * Delete record by id
     * @param $id of the record
     * @param $table where the record is stored
     */
    public function delete($id, $table){
        $this->database->delete($id,$table);
    }

    /**
     * Redirect user to another page
     * @param $newURL
     */
    public function redirect($newURL){
        header("Location: " . $newURL);
        die();
    }

    /**
     * Get the last inserted record
     * @return mixed
     */
    public function insertId(){
        // Get last inserted id
        return $this->database->getConnection()->insert_id;
    }

    /**
     * Display data in an array
     * @param $array
     */
    public function var_dump($array){
        echo "<pre>";
        print_r($array); // or var_dump($data);
        echo "</pre>";
    }

    /**
     * Check if the user exists, and that the authentication returns true.
     * @param $username
     * @param $password
     * @return bool true if the user- password data is correct
     */
    public function login($username, $password){
        return $this->user->login($username,$password);
    }


    public function connectedToDatabase(){
        $isConnected = true;

         if($this->database->getConnection() ==null){
             $isConnected = false;
         }

         return $isConnected;
    }


}
