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
    private $thumbNail;
    private $filePath;
    private $cmdResultVideo;
    private $cmdOutputVideo;
    private $cmdResultImg;
    private $cmdOutputImg;
    private $message;
    private $lastCommand;
    private $databse;

    function __construct() {
        // Set download directory
        $this->downloadDirectory = "/Applications/MAMP/htdocs/cinema/downloads";

        $this->database = new Database();

    }

    private function cleanPath ($str){
        $search  = array(' ', '&', 'Å', 'Ä', 'Ö', 'å', 'ä', 'ö', "'", "\"");
        $replace = array('\ ', '\&','A', 'A', 'O', 'a', 'a','o', "\\'", "\\\"");
        $str = str_replace($search, $replace, $str);
        return $str;
    }

    public function cleanNameForThumbnail ($str){
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


    private function dump($arr){
        echo "<pre>" . var_dump($arr) . "</pre>";
    }

    public function getLastCreatedFile(){
        $command = "ls -Art {$this->downloadDirectory}/*.mp4 | tail -n 1";
        exec($command, $output, $ret);
        if($ret ==0){
            return $output[0];
        }else{
            // Fail
            return false;
        }
    }

    private function downloadYoutubeVideo(){

        $number = $this->database->getLastItemId() + 1;

        //$this->lastCommand = $youtubeDL = "/usr/local/bin/youtube-dl -o \"$this->downloadDirectory/%(title)s.%(ext)s\" --write-thumbnail "   . $this->url;
        $this->lastCommand = $youtubeDL = "/usr/local/bin/youtube-dl -o \"$this->downloadDirectory/$number.%(ext)s\" --write-thumbnail "   . $this->url;

        exec($youtubeDL, $output, $ret);
        if($ret ==0){

            $data = $output[count($output) - 2];                    // Get the real file name, downloaded by youtube-dl
            $filePath = substr($data, strpos($data, "/"));

            // Set file name
            $tmpArray = explode( "/", $filePath);
            $this->realFileName = end($tmpArray);
            echo $this->thumbNail = current(explode("." , $this->realFileName));
            $this->filePath = $this->cleanPath($filePath);

            return true;
        }else{
            // Fail
            $this->cmdOutputVideo = $output;
            $this->cmdResultVideo = $ret;
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
                return true;
                /*
                if($this->createThumbnail()){
                    $this->message['Thumbnail_Status'] = "Thumbnail created";
                    $this->message['Status'] = "Success";
                    return true;
                }else{
                    $this->message['Status'] = "Could not create thumbnail image: convert name: " . $this->lastCommand;
                    return false;
                }
                */
            }else{
                $this->message['Status'] = "Could not download YouTube video: " . $this->lastCommand;
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
        $this->lastCommand = $thumbnail = "/usr/local/bin/ffmpeg -itsoffset -1 -i " . $this->filePath . " -vframes 1 -filter:v scale=\"400:-1\"  " . $this->downloadDirectory . "/" . $this->thumbNail . ".png";

        exec($thumbnail, $output, $ret);

        if($ret ==0){
            // Success
            return true;
        }else{
            // Fail
            $this->cmdOutputImg = $output;
            $this->cmdResultImg = $ret;
            return false;
        }

    }

    /**
     * Shows errors from the terminal
     */
    public function showErrors(){

        if($this->cmdResultVideo !=0){
            echo "<b>CMD response video:</b>";
            echo "<pre>";
            echo var_dump($this->cmdOutputVideo);
            echo "</pre>";
        }
        if($this->cmdResultImg !=0) {
            echo "<b>CMD response thumbnail:</b>";
            echo "<pre>";
            echo var_dump($this->cmdOutputImg);
            echo "</pre>";
        }
    }


    public function getThumbnailPath(){
        return "downloads/" . $this->thumbNail . ".jpg";
    }

    public function deleteVideo(){
        $video = "dowloads/" . $this->thumbNail . ".mp4";
        if(file_exists($video)){
            unlink($video);
        }
    }


    public function deleteFiles(){

        $thumbnail = "downloads/" . $this->thumbNail . ".jpg";
        $video = "dowloads/" . $this->thumbNail . ".mp4";

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
    public function getThumbNail()
    {
        return $this->thumbNail;
    }

    /**
     * @param mixed $thumbNail
     */
    public function setThumbNail($thumbNail)
    {
        $this->thumbNail = $thumbNail;
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
