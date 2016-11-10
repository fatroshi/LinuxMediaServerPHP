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

class Controller {

    private $database;
    private $media;
    private $player;

    function __construct() {
        $this->database = new Database();                                       // Connect to the database
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

    public function getAllItems(){

        $output = "";
        $sql = "SELECT * FROM Items";

        $conn = $this->database->getConnection();
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $filePath = $row['Path'];
                $status = $row['Status'];
                $fileName = $row['FileName'];
                $thumbnail = $row['Thumbnail'];

                $output .="<div class=\"col-sm-6 col-md-5\">";
                    $output .="<div class=\"thumbnail embed-responsive embed-responsive-16by9\">";
                        $output .= "<video  id='{$filePath}' width='430' height='245' poster='{$thumbnail}' controls>";
                        $output .= "<source src='downloads/{$fileName}' type='video/mp4'  >";
                        $output .= "</video>";
                    $output .="</div>";
                $output .="</div>";


            }
        } else {
            //return "No records found";
        }

        return $output;
    }


    public function getItemById($id){

        $output = "";
        $sql = "SELECT * FROM Items WHERE id={$id}";

        $conn = $this->database->getConnection();
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $filePath = $row['Path'];
                $status = $row['Status'];
                $fileName = $row['FileName'];
                $thumbnail = $row['Thumbnail'];

                $output .="<div class=\"col-sm-6 col-md-5\">";
                    $output .="<div class=\"thumbnail embed-responsive embed-responsive-16by9\">";
                        $output .= "<video  id='{$filePath}' width='430' height='245' poster='{$thumbnail}' controls>";
                            $output .= "<source src='downloads/{$fileName}' type='video/mp4'  >";
                        $output .= "</video>";
                    $output .="</div>";
                $output .="</div>";
            }
        } else {
            //return "No records found";
        }

        return $output;
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
