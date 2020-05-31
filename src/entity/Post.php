<?php


namespace src\entity;


use System\model\Model;
use System\model\Record;

class Post extends Model
{
    private string $title;

    private string $like;

    private string $content;

    private string $public;


//    public function __construct()
//    {
//        $this->fieldInitialization($this);
//    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $like
     */
    public function setLike(string $like): void
    {
        $this->like = $like;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param string $public
     */
    public function setPublic(string $public): void
    {
        $this->public = $public;
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLike(): string
    {
        return $this->like;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPublic(): string
    {
        return $this->public;
    }


}