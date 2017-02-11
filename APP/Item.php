<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-06
 * Time: 23:00
 */
class Item
{
    private $type;
    private $path;
    private $status;
    private $name;
    private $thumbnail;


    public function __construct($name, $type, $path, $thumbnail)
    {
        $this->name = $name;
        $this->type = $type;
        $this->path = $path;
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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


}