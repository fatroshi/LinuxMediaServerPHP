<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 02/10/16
 * Time: 22:43
 */
class Controller {

    private $database;


    function __construct() {
        $this->database = new Database();                                       // Connect to the database

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
}
