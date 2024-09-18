<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all posts that belong to the authenticated user
        $posts = Post::where('user_id', Auth::id())->get();

        return $this->successResponse($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        // Get authenticated user
        $user = Auth::user();

        // Validate request
        $validated = $request->validated();

        if ($request->hasFile('images')) {
            // Handle file upload
            $image = $request->file('images');
            $image_new_name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $image_new_name);

            // Save image path in validated data
            $validated['images'] = 'uploads/posts/' . $image_new_name;
        }

        // Associate the post with the authenticated user
        $validated['user_id'] = $user->id;

        // Create the post
        $post = Post::create($validated);

        return $this->successResponse($post, 'New Post Created!', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the post that belongs to the authenticated user
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return $this->errorResponse('Post Not Found or Unauthorized', 404);
        }

        return $this->successResponse($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        // Get the post that belongs to the authenticated user
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return $this->errorResponse('Post Not Found or Unauthorized', 404);
        }

        $validated = $request->validated();

        if ($request->hasFile('images')) {
            // Delete the old image if it exists
            if ($post->images && file_exists(public_path($post->images))) {
                unlink(public_path($post->images));
            }

            // Handle file upload
            $image = $request->file('images');
            $image_new_name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $image_new_name);

            // Update the image path in the validated data
            $validated['images'] = 'uploads/posts/' . $image_new_name;
        }

        // Update the post
        $post->update($validated);

        return $this->successResponse($post, 'Post Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the post that belongs to the authenticated user
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return $this->errorResponse('Post Not Found or Unauthorized', 404);
        }

        // Delete the image if it exists
        if ($post->images && file_exists(public_path($post->images))) {
            unlink(public_path($post->images));
        }

        // Delete the post
        $post->delete();

        return $this->successResponse(null, 'Post Deleted', 200);
    }

   
}
