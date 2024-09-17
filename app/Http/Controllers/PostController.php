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
        // Get all posts (You can filter posts for authenticated users if necessary)
        $posts = Post::all();

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
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post Not Found');
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
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post Not Found');
        }

        // Check if the authenticated user owns the post
        if ($post->user_id !== Auth::id()) {
            return $this->errorResponse('Unauthorized', 403);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post Not Found');
        }

        // Check if the authenticated user owns the post
        if ($post->user_id !== Auth::id()) {
            return $this->errorResponse('Unauthorized', 403);
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
