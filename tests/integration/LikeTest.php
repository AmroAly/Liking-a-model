<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test */
    public function a_user_can_like_a_post()
    {
    	// given I have a post
    	$post = factory(App\Post::class)->create();
    	// and a user
    	$user = factory(App\User::class)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user);
    	
    	// when they like a post
    	$post->like();
    	// then we should see evidence in the database
    	$this->seeInDatabase('likes', [
    		'user_id' => $user->id,
    		'likeable_id' => $post->id,
    		'likeable_type'	=> get_class($post)
    	]);

    	$this->assertTrue($post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post()
    {
    	 	// given I have a post
    	$post = factory(App\Post::class)->create();
    	// and a user
    	$user = factory(App\User::class)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user);
    	
    	// when they like a post
    	$post->like();

    	$post->unlike();
    	// then we should see evidence in the database
    	$this->notSeeInDatabase('likes', [
    		'user_id' => $user->id,
    		'likeable_id' => $post->id,
    		'likeable_type'	=> get_class($post)
    	]);

    	$this->assertFalse($post->isLiked());
    }

    public function a_user_may_toggle_a_posts_like_status()
    {
		  	// given I have a post
    	$post = factory(App\Post::class)->create();
    	// and a user
    	$user = factory(App\User::class)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user);
    	
    	$post->toggle();
    	$this->assertTrue($post->isLiked());

	   	$post->toggle();
	    
    	$this->assertFalse($post->isLiked());

    }

    public function a_post_knows_how_many_likes_it_has()
    {
    	$post = factory(App\Post::class)->create();
    	// and a user
    	$users = factory(App\User::class, 2)->create();
    	
    	// and that user is logged in
    	$this->actingAs($user[0]);
    	$post->like();

    	$this->actingAs($user[1]);
    	$post->like();

    	$this->assertTrue(2, $post->likesCount());
    }
}


