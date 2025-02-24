<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();
        return new JsonResponse([
            'data' => PostResource::collection($posts),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());

        return new JsonResponse(
            [
                'message' => 'Post created',
                'data' => new PostResource($post),
            ],
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        return new JsonResponse(
            [
                'data' => new PostResource($post),
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        $validated = $request->validated();

        $post = Post::findOrFail($id);

        $post->update($validated);

        return new JsonResponse(
            [
                'message' => 'Post updated',
                'data' => new PostResource($post),
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        return new JsonResponse(
            [
                'message' => 'Post deleted',
            ],
            Response::HTTP_OK,
        );
    }
}
