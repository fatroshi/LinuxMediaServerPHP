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
include_once ("Upload.php");

class Controller {

    private $database;
    private $category;
    private $media;
    private $player;

    function __construct() {
        $this->media = new Media();
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

    public function addCategory($name, $image){


        $imagePath = $this->media->getDownloadDirectory();

        $imageName = $image["name"];
        $imageName = $this->media->cleanNameForThumbnail($imageName);
        $fileNameAndExt = explode(".", $imageName);
        $fileName = current($fileNameAndExt);

        $handle = new upload($image);
        if ($handle->uploaded) {
            //$handle->file_new_name_body   = 'image_resized';
            $handle->file_new_name_body   = $fileName;
            $handle->image_resize         = false;
            $handle->image_x              = 100;
            $handle->image_ratio_y        = true;
            $handle->process($imagePath);
            if ($handle->processed) {
                //echo 'image resized';
                $handle->clean();

                // Add category to db
                $sql = "INSERT INTO categories (title,img) values ('{$name}','{$imageName}')  ";
                if($this->database->getConnection()->query($sql) === true){
                    return true;
                }else{
                    echo "Error: " . $sql . "<br>" . $this->database->error;
                    return false;
                }
            } else {
                echo 'error : ' . $handle->error;
            }
        }
    }


    public function getAllCategories(){

        $output = "";

        $sql = "SELECT * FROM categories";
        $conn = $this->database->getConnection();
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $title = $row['title'];
                $id = $row['id'];
                $img = $row['img'];

                if($img == ""){
                    $output .="<div class=\"col-sm-6 col-md-5\">";
                    $output .="<div class=\"thumbnail embed-responsive embed-responsive-16by9\">";
                    $output .= "[ Link add image ]";
                    $output .="</div>";
                    $output .= "<a href='?category={$id}'>{$title}</a>";
                    $output .= " <a href='?category={$id}'><span class='glyphicon glyphicon-picture'></span></a>";
                    $output .="</div>";
                }else{
                    $output .="<div class=\"col-sm-6 col-md-5\">";
                        $output .="<div class=\"thumbnail embed-responsive embed-responsive-16by9\">";
                        $output .= "<a href='?category={$id}'><img src='" ."downloads/" .$img . "' border='0'></a>";
                        $output .="</div>";
                    $output .= "<a href='?category={$id}'>{$title}</a>";
                    $output .="</div>";
                }

            }
        }

        return $output;
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
