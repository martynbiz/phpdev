<?php

namespace app\Controller;

use app\Model\Post;

/**
* UserController
*    
* desc
*    
*/
class PostsController extends ApplicationController
{
    /**
    * indexAction
    *    
    * List all posts
    *    
    */
    public function indexAction()
    {
        $result = Post::all()->toArray();
        
        $this->view('posts/index', array(
            'posts' => $result,
        ));
    }
}
