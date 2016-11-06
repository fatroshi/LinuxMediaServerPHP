<?php
/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-06
 * Time: 21:36
 */

include_once ("../App/Controller.php");

$controller = new Controller();

if($_POST['bar'] != "") {
    $controller->download($_POST['bar']);
    echo "ok";
}else{

}



