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

    function __construct() {
        // Set download directory
        $this->downloadDirectory = "/Applications/MAMP/htdocs/cinema/downloads";

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

    /*
     * FIX!!!!
     * Use find . -ctime -5 | tail -1 find the newest file
     * the set path, realfilename and clean up for thumnnail
     *
     * So a new function needs to be created and called in the function download
     *
     * public function setFileAttributes(){
     *  ...
     *
     *
     */

    private function dump($arr){
        echo "<pre>" . var_dump($arr) . "</pre>";
    }

    public function getLastCreatedFile(){

        $command = "find {$this->downloadDirectory} -ctime -5 | tail -1";

        exec($command, $output, $ret);
        if($ret ==0){
            return $output[0];
        }else{
            // Fail
            return false;
        }
    }

    private function downloadYoutubeVideo(){
        $youtubeDL = "/usr/local/bin/youtube-dl -o \"$this->downloadDirectory/%(title)s.%(ext)s\" " . $this->url;
        exec($youtubeDL, $output, $ret);
        if($ret ==0){

            $data = $this->getLastCreatedFile();
            $filePath = substr($data, strpos($data, "/"));

            // Set file name
            $tmpArray = explode( "/", $filePath);
            $this->realFileName = end($tmpArray);
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

    /**
     * Checks if the video was downloaded and that the thumbnail was created.
     * For each step the status is stored in the message array.
     *
     * @return bool true if video was downloaded and thumbnail created
     */
    public function download(){
        if($this->isYouTubeLink()){
            $this->message['Start'] = "Starting to download...";
            $this->message['Type'] = "Video from youtube.";
            if($this->downloadYoutubeVideo()){
                $this->message['Download_Status'] = "Youtube video downloaded: " . $this->realFileName;
                if($this->createThumbnail()){
                    $this->message['Thumbnail_Status'] = "Thumbnail created";
                    $this->message['Status'] = "Success";
                    return true;
                }else{
                    $this->message['Status'] = "Could not create thumbnail image:";
                    return false;
                }
            }else{
                $this->message['Status'] = "Could not download YouTube video: " . $this->realFileName;
                return false;
            }
        }else{
            $this->message['Status'] = "Unknown url: " . $this->url;
            return false;
        }
    }

    /**
     * @return bool true if the thumbnail could be created
     */
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

    /**
     * Shows errors from the terminal
     */
    public function showErrors(){
        echo "<b>CMD response:</b>";
        echo "<pre>";
        echo var_dump($this->cmdOutput);
        echo "</pre>";
        echo "<pre>";
        echo var_dump($this->cmdResult);
        echo "</pre>";
    }

    /**
     * @return bool true if possible to play the video
     */
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
        return "downloads/" . $this->fileName . ".png";
    }

    public function deleteVideo(){
        $video = "dowloads/" . $this->fileName;
        if(file_exists($video)){
            unlink($video);
        }
    }


    public function deleteFiles(){

        $thumbnail = "downloads/" . $this->fileName . ".png";
        $video = "dowloads/" . $this->fileName;

        if(file_exists($thumbnail)){
            unlink($thumbnail);
        }

        if(file_exists($video)){
            unlink($video);
        }
    }

    public function showMessages(){
        echo "<pre>";
        echo var_export($this->message);
        echo "</pre>";
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getDownloadDirectory()
    {
        return $this->downloadDirectory;
    }

    /**
     * @param string $downloadDirectory
     */
    public function setDownloadDirectory($downloadDirectory)
    {
        $this->downloadDirectory = $downloadDirectory;
    }

    /**
     * @return mixed
     */
    public function getRealFileName()
    {
        return $this->realFileName;
    }

    /**
     * @param mixed $realFileName
     */
    public function setRealFileName($realFileName)
    {
        $this->realFileName = $realFileName;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }



}