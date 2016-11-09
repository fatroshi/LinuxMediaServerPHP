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



    public function setPost($post){
        $this->post = $post;
    }


    /**
     * Check the $_Post and assign new tasks to the list (OBS!!! Not added to the list yet!!!!)
     */
    public function performTask(){
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


    /**
     * Handles all Media methods
     *
     * @param $command the command name
     * @param $value value of the command
     */
    public function media($command, $value){
        switch ($command){
            case "download";
                // Get post url;
                //Download the file comma
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