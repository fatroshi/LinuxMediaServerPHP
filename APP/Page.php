<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-06
 * Time: 22:58
 */
class Page
{
    private $title;
    private $items;


    public function __construct($title)
    {
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }


}