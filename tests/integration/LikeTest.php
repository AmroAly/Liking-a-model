<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeTest extends TestCase
{
    use DatabaseTransactions;

    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = factory(App\Post::class)->create();
    }
    
    /** @test */
    public function a_user_can_like_a_post()
    {
    	$this->signIn();
    	
    	// when they like a post
    	$this->post->like();
    	// then we should see evidence in the database
    	$this->seeInDatabase('likes', [
    		'user_id' => $this->user->id,
    		'likeable_id' => $this->post->id,
    		'likeable_type'	=> get_class($this->post)
    	]);

    	$this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post()
    {
    	// and a user
    	$user = factory(App\User::class)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user);
    	
    	// when they like a post
    	$this->post->like();

    	$this->post->unlike();
    	// then we should see evidence in the database
    	$this->notSeeInDatabase('likes', [
    		'user_id' => $user->id,
    		'likeable_id' => $this->post->id,
    		'likeable_type'	=> get_class($this->post)
    	]);

    	$this->assertFalse($this->post->isLiked());
    }

    public function a_user_may_toggle_a_posts_like_status()
    {
    	// and a user
    	$user = factory(App\User::class)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user);
    	
    	$this->post->toggle();
    	$this->assertTrue($this->post->isLiked());

	   	$this->post->toggle();
	    
    	$this->assertFalse($this->post->isLiked());

    }

    public function a_post_knows_how_many_likes_it_has()
    {
    	// and a user
    	$users = factory(App\User::class, 2)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user[0]);
    	$this->post->like();

    	$this->actingAs($user[1]);
    	$this->post->like();

    	$this->assertTrue(2, $this->post->likesCount());
    }
}


