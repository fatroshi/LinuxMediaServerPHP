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
    private $controlFile;
    private $directory;
    private $username;

    public function __construct()
    {

        //Path to file
        $this->directory = "/Applications/MAMP/htdocs/cinema/downloads";                // Need the absolute path for apache
        $this->controlFile = "{$this->directory}/mplayer";

        // Create control file if it doesn't exist
        $this->createControlFile();

    }


    private function createControlFile(){
        if(!file_exists($this->controlFile)){
            $mode=0666;
            // create the pipe
            umask(0);
            posix_mkfifo($this->controlFile,$mode);
        }
    }

    public function createDirectory($dir){
        // create new directory with 744 permissions if it does not exist yet
        // owner will be the user/group the PHP script is run under
        if ( !file_exists($dir) ) {
            $oldmask = umask(0);  // helpful when used in linux server
            mkdir ($dir, 0744);
        }

        file_put_contents ($dir.'/test.txt', 'Hello File');
    }

    /**
     * @return bool true if possible to play the video
     */
    public function play($filePath){
        $play = "/usr/local/bin/mplayer -slave -input file={$this->controlFile} \"{$filePath}\"";
        if(!$this->sendCommand($play)){
            $this->showErrors();
            return false;
        }else{
            return true;
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
        $play = "echo \"pause\" > {$this->controlFile}";
        if(!$this->sendCommand($play)){
            $this->showErrors();
        }
    }

    public function pause(){
        $pause = "echo \"pause\" > {$this->controlFile}";
        if(!$this->sendCommand($pause)){
            $this->showErrors();
        }

    }

    public function setVolym($volym){

        $command = "";

        if($volym == "up"){
            $command = "set_property volume 80";
        }else if($volym == "down"){
            $command = "set_property volume 0";
        }

        $pause = "echo \"{$command}\" > {$this->controlFile}";
        if(!$this->sendCommand($pause)){
            $this->showErrors();
        }

    }

    public function seek($value){
        $pause = "echo seek \"{$value}\" > {$this->controlFile}";
        if(!$this->sendCommand($pause)){
            $this->showErrors();
        }
    }

    public function quit(){
        $pause = "echo \"quit\" > {$this->controlFile}";
        if(!$this->sendCommand($pause)){
            $this->showErrors();
        }

    }

    public function getControlFile(){
        return $this->controlFile;
    }

    public function whoAmI(){
        $processUser = posix_getpwuid(posix_geteuid());
        echo($processUser['name']);
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