<?php

class ApiPostResponse
{
    public $post;
    public $postData;

    /**
     * ApiPostResponse constructor.
     */
    public function __construct()
    {
    }

    public function build() {
        return [
          "post" => $this->post,
          "post_data" => $this->postData
        ];
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * @param mixed $postData
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
    }

}