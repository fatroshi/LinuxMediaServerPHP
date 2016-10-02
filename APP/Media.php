<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 02/10/16
 * Time: 20:44
 */
class Media
{

    private $url;
    private $downloadDirectory;
    private $realFileName;
    private $fileName;
    private $filePath;
    private $cmdResult;
    private $cmdOutput;
    private $message;

    function __construct($url) {
        $this->url = $url;
        // Set download directory
        $this->downloadDirectory = "/Applications/MAMP/htdocs/cinema/test";

    }


    private function cleanPath ($str){
        $search  = array(' ', '&', 'Å', 'Ä', 'Ö', 'å', 'ä', 'ö', "'", "\"");
        $replace = array('\ ', '\&','A', 'A', 'O', 'a', 'a','o', "\\'", "\\\"");
        $str = str_replace($search, $replace, $str);
        return $str;
    }

    private function cleanNameForThumbnail ($str){
        $search  = array(' ', '&', 'Å', 'Ä', 'Ö', 'å', 'ä', 'ö', "'", "\"", '|', '/', '-');
        $replace = array('', 'and', 'A',  'A', 'O', 'a', 'a', 'o','','','.', '','_');
        $str = str_replace($search, $replace, $str);
        return $str;
    }

    private function isYouTubeLink(){
        if (strpos($this->url, 'youtube.com') !== false) {
            return true;
        }else{
            return false;
        }
    }

    private function downloadYoutubeVideo(){
        $youtubeDL = "/usr/local/bin/youtube-dl -o '{$this->downloadDirectory}/%(title)s.%(ext)s' '{$this->url}' ";
        exec($youtubeDL, $output, $ret);
        if($ret ==0){
            // Get file path
            $data = $output[3];
            $filePath = substr($data, strpos($data, "/"));

            // Set file name
            $this->realFileName = end(explode( "/", $filePath));
            $this->fileName = $this->cleanNameForThumbnail($this->realFileName);
            $this->filePath = $this->cleanPath($filePath);


            return true;
        }else{
            // Fail
            $this->cmdOutput = $output;
            $this->cmdResult = $ret;
            return false;
        }

    }

    public function download(){
        $this->message['Start'] = "Staring download...";
        if($this->isYouTubeLink()){
            $this->message['Type'] = "Video from youtube.";
            if($this->downloadYoutubeVideo()){
                $this->message['Download_Status'] = "Youtube video downloaded: " . $this->realFileName;
                if($this->createThumbnail()){
                    $this->message['Thumbnail_Status'] = "Thumbnail created";
                    $this->message['Status'] = "Success";
                }else{
                    $this->message['Status'] = "Could not create thumbnail image";
                    return false;
                }
            }else{
                $this->message['Status'] = "Could not download YouTube video: " . $this->realFileName;
                return false;
            }
        }else{
            $this->message['Status'] = "Unknown url...";
            return false;
        }
    }

    public function createThumbnail(){

        $thumpnail = "/usr/local/bin/ffmpeg -itsoffset -1 -i " . $this->filePath . " -vframes 1 -filter:v scale=\"400:-1\"  " . $this->downloadDirectory . "/" . $this->fileName . ".png";
        exec($thumpnail, $output, $ret);

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

    public function showErrors(){
        echo "<pre><b>Something whent wrong:</b> <br/>CMD</pre>";
        echo "<pre>";
        echo var_export($this->cmdOutput);
        echo "</pre>";
        echo "<pre>";
        echo var_export($this->cmdResult);
        echo "</pre>";
    }

    public function play(){
        $play = "/usr/local/bin/mplayer " . $this->filePath ;
        exec($play, $output, $ret);

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

    public function getThumbnailPath(){

        // OBS !! WRONG, use only parent folder name (test) and file name!!!!
        return $this->downloadDirectory . "/" . $this->fileName . ".png";
    }

}