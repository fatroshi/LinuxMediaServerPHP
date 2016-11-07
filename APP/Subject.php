<?php

/**
 * Created by PhpStorm.
 * User: Elise
 * Date: 2016-11-07
 * Time: 20:06
 */
class Subject
{
    private $id;
    private $title;
    private $pages;


    public function __construct()
    {

    }

    public function addPage($page){
        $this->pages[] = $page;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }



}