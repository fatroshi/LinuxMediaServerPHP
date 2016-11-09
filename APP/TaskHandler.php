<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-09
 * Time: 11:19
 */
class TaskHandler
{

    private $post;
    private $tasks;



    public function setPost(){
        $this->post;
    }

    public function taskHandler($task){


    }

    public function media($value){
        switch ($value){
            case "download";
                // Get post url;
                $url = $this->getTask("url");

                break;
        }
    }

    public function getTask(){
        foreach ($_POST as $key => $value) {
            if($key == "url"){
                return $value;
                break;
            }
        }
    }

    public function checkPost(){
        foreach ($_POST as $key => $value) {
            echo $key . " " . $value;
        }
    }

    public function addTask(){

    }

    public function taskExists(){

    }

}