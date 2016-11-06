<?php
/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-06
 * Time: 21:36
 */

include_once ("../App/Controller.php");

$controller = new Controller();

if($_POST['url'] != "") {
    $controller->download($_POST['url']);



    echo "<div class=\"alert alert-success\" role=\"alert\">Download completed</div>";
}else{
    echo "<div class=\"alert alert-danger\" role=\"alert\">Download completed</div>";
}



