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
    private $categoryId;


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

        if(isset($this->post['task'])){

            $task       = $this->post['task'];
            $command    = $this->post['command'];
            $value      = $this->post['value'];

            if(isset($this->post['category'])){
                $category   = $this->post['category'];
            }else{
                $category = 0;
            }


            switch ($task){
                case "media":
                    // Store category id
                    $this->categoryId = intval($category);
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

    private function downloadMediaFile($url){
        // Get post url;
        $this->media->setUrl($url);
        if($this->media->download()){
            $this->database->saveMediaFile($this->media, $this->categoryId);
            $id = $this->database->getLastId();
            echo $this->database->getItemById($id);
            echo "<div class=\"alert alert-success\" role=\"alert\">Download completed</div>";      // Should fix this... find a better way

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
                $this->player->play($value);
                break;
            case "resume":
                // Resume
                $this->player->resume();
                break;
            case "pause":
                // Pause video
                $this->player->pause();
                break;
            case "quit":
                // Pause video
                $this->player->quit();
                break;
            case "volym":
                // Pause video
                $this->player->setVolym($value);
                break;
            case "seek":
                //seek
                $this->player->seek($value);
            default:
                break;

        }
    }

    public function addTask(){

    }

    public function taskExists(){

    }
}