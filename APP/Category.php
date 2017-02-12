<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2017-02-11
 * Time: 13:16
 */
class Category
{
    private $id;
    private $title;
    private $img;
    private $items = array();
    private $database;

    function __construct($database) {
        $this->database = $database;    // Connect to the database
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function addItem($item){
        $this->items[] = $item;
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
                $output .= "<video  id=\"{$filePath}\" width='430' height='245' poster='{$thumbnail}' controls>";
                $output .= "<source src=\"downloads/{$fileName}\" type='video/mp4'  >";
                $output .= "</video>";
                $output .="</div>";
                $output .="</div>";


            }
        } else {
            //return "No records found";
        }

        return $output;
    }

    public function getCategoryItems($id){
        $output = "";
        $sql = "SELECT * FROM Items WHERE category_id = {$id}";

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
                $output .="<div id='{$filePath}' class='tv'><h1 class='glyphicon glyphicon-blackboard'></h1></div>";
                $output .="<div class=\"thumbnail embed-responsive embed-responsive-16by9\">";
                $output .= "<video width='430' height='245' poster='{$thumbnail}' controls>";
                $output .= "<source src=\"downloads/{$fileName}\" type='video/mp4'  >";
                $output .= "</video>";
                $output .="</div>";
                $output .="</div>";


            }
        } else {
            //return "No records found";
        }

        return $output;
    }

}