<?php
/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-06
 * Time: 21:36
 */

include_once ("../App/Controller.php");

$controller = new Controller();

if(isset($_POST['url']) && $_POST['url'] != "") {
    $url = $_POST['url'];

    $media = $controller->getMedia();
    $media->setUrl($url);

    if($media->download()){
        echo "<div class=\"alert alert-success\" role=\"alert\">Download completed</div>";
        $controller->saveItem();
        echo $controller->getAllItems();
    }else{
        echo "<div class=\"alert alert-danger\" role=\"alert\">";
            $media->deleteFiles();
            $media->showErrors();
            $media->showMessages();
        echo "</div>";
    }

}else{
    echo "<div class=\"alert alert-danger\" role=\"alert\">Please type in an URL...</div>";
}



