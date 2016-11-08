<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-07
 * Time: 19:41
 *
 * New to install "pidof" --> sudo apt-get pidof
 * kill -STOP pid --> Pause
 * kill -cont pid --> Resume
 *
 * Or combine way --> kill -STOP $(pidof mplayer)
 *
 * A better way is to start mplayer in slave mode
 *
 * example:
 * mkfifo mplayer-control
 * mplayer -slave -input file=./mplayer-control videoFile
 *
 * echo "pause" > mplayer-control
 * echo "quit" > mplayer-control
 */
class Player
{
    private $cmdOutput;
    private $cmdResult;

    /**
     * @return bool true if possible to play the video
     */
    public function play($filePath){
        $play = "/usr/local/bin/mplayer \"{$filePath}\"";
        if(!$this->sendCommand($play)){
            $this->showErrors();
        }
    }

    public function showErrors(){
        echo "<pre>";
        var_dump($this->cmdResult);
        echo "</pre>";
        echo "<pre>";
            var_dump($this->cmdOutput);
        echo "</pre>";
    }

    public function resume(){
        $resume = "kill -STOP $(pidof mplayer)";
        if(!$this->sendCommand($resume)){
            $this->showErrors();
        }
    }

    public function pause(){
        $pause = "kill -CONT $(pidof mplayer)";
        if(!$this->sendCommand($pause)){
            $this->showErrors();
        }
    }


    public function whoAmI(){
        $this->sendCommand("whoami");
        $this->showErrors();
    }

    private function sendCommand($command){
        exec($command, $output, $ret);

        if($ret ==0){
            // Success
            return true;
        }else{
            // Fail
            $this->cmdOutput = $output;
            $this->cmdResult = $ret;
            return false;
        }
    }

}