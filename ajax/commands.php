<?php
/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-06
 * Time: 21:36
 */


include_once ("../App/TaskHandler.php");

$handler = new TaskHandler();
if(isset($_POST)) {

    // TODO Check if category exists in db
    // The class for navigation should handle this!!! OR Ajax??

    $handler->setPost($_POST);
    $handler->assignTask();
}