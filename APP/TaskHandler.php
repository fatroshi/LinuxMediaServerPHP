<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-09
 * Time: 11:19
 */

include_once ("Media.php");
include_once ("Player.php");
include_once ("Database.php");

class TaskHandler
{

    private $database;
    private $media;
    private $player;
    private $post;
    private $tasks;


    public function TaskHandler(){
        $this->database = new Database();                                       // Connect to the database
        $this->media = new Media();
        $this->player = new Player();
    }

    public function setPost($post){
        $this->post = $post;
    }


    /**
     * Check the $_Post and assign new tasks to the list (OBS!!! Not added to the list yet!!!!)
     */
    public function assignTask(){
        foreach ($this->post as $action => $actionName){
            foreach ($actionName as $command => $value){
                echo "Action: " . $action . " command: " . $command . " value: " . $value . " </br>";

                switch ($action){
                    case "media":
                        $this->media($command,$value);
                        break;
                    case "player":
                        $this->player($command, $value);
                        break;
                    default:
                        // Handle errors
                        break;
                }

            }
        }
    }

    private function downloadMediaFile($url){
        // Get post url;
        $this->media->setUrl($url);
        if($this->media->download()){
            $this->database->saveMediaFile($this->media);
        }else {
            echo "<div class=\"alert alert-danger\" role=\"alert\">";
            $this->media->deleteFiles();
            $this->media->showErrors();
            $this->media->showMessages();
            echo "</div>";
        }
    }

    /**
     * Handles all Media methods
     *
     * @param $command the command name
     * @param $value value of the command
     */
    public function media($command, $value){
        switch ($command){
            case "download";
                $this->downloadMediaFile($value);
                break;
        }
    }

    /**
     * Handles all Player methods
     *
     * @param $command the command name
     * @param $value value of the command
     */
    public function player($command, $value){
        switch ($command){
            case "play";
                // Start mplayer and play video
                break;
            case "resume":
                // Resume
                break;
            case "pause":
                // Pause video
                break;
            default:
                break;

        }
    }


    public function addTask(){

    }

    public function taskExists(){

    }

}