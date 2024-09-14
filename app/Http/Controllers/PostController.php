<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::get();


          return  $this->SuccessResponse($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        //
    //   $posts = new Post ;
    //   $posts->title = $request->title;
    //   $posts->content = $request->content;
    //   $posts->save();

     $posts = Post::create($request->validated());


       return $this->successResponse($posts, 'New Post Created!!',201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::whereId($id)->first();
        return $this->successResponse($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //

        // $post->title = $request->title ?? $post->title;
        // $post->content = $request->content ?? $post->content;
        // $post->save();
        $post->update($request->validated());

        return $this->successResponse($post, 'Updated Post');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        // return response()->json([
        //     "messages"=> "Post Deleted",
        //     "posts"=> $post->delete(),
        //   ],200 );
        $post->delete();
        return $this->successResponse(null, 'Post Deleted',201);
    }
}
