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



    $validated = $request->validated();

    if ($request->hasFile('images')) {
        // Get the uploaded file
        $image = $request->file('images');

        // Create a new name for the image (time + original file name)
        $image_new_name = time() . '_' . $image->getClientOriginalName();

        // Move the image to the 'uploads/posts' directory in the public folder
        $image->move(public_path('uploads/posts'), $image_new_name);

        // Save the relative path to the 'images' column in the database
        $validated['images'] = 'uploads/posts/' . $image_new_name;
    }
     $posts = Post::create($validated);


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
        if(!$post){
          return  $this->errorResponse('Post Not Found');
        }
        return $this->successResponse($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $id)
    {
        //
        // $post->title = $request->title ?? $post->title;
        // $post->content = $request->content ?? $post->content;
        // $post->save();


         // Validate incoming data
         $post = Post::whereId($id)->first();
         if(!$post){
            return  $this->errorResponse('Post Not Found');
         }
        $validated = $request->validated();

        if ($request->hasFile('images')) {
            // Delete the old image if it exists
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            // Handle the file upload
            $image = $request->file('images');
            $image_new_name = time() . '_' . $image->getClientOriginalName(); // Generate new image name
            $image->move(public_path('uploads/posts'), $image_new_name); // Move the image to the 'uploads/students' directory

            // Save the image path in the database
            $validated['images'] = 'uploads/posts/' . $image_new_name;
        }
        $post->update($validated);

        return $this->successResponse($post, 'Updated Post');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $id)
    {
        //
        // return response()->json([
        //     "messages"=> "Post Deleted",
        //     "posts"=> $post->delete(),
        //   ],200 );
        $post = Post::whereId($id)->first();
        if(!$post){
            return $this->errorResponse();
         }
        $post->delete();
        return $this->successResponse(null, 'Post Deleted',201);
    }



}
