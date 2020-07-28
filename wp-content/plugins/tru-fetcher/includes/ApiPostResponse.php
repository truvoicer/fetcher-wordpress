<?php

class ApiPostResponse
{
    public $post;
    public $listings_block_data;
    public $site_config;

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
    public function getListingsblockdata()
    {
        return $this->listings_block_data;
    }

    /**
     * @param mixed $listings_block_data
     */
    public function setListingsblockdata($listings_block_data)
    {
        $this->listings_block_data = $listings_block_data;
    }

	/**
	 * @return mixed
	 */
	public function getSiteConfig() {
		return $this->site_config;
	}

	/**
	 * @param mixed $site_config
	 */
	public function setSiteConfig( $site_config ) {
		$this->site_config = $site_config;
	}

}