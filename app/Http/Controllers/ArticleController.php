<?php

namespace App\Http\Controllers;

use App\Models\Article; // Make sure this path is correct
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArticleController extends Controller
{

  
    // Get a list of all Articles
    public function index()
    {
        $articles = Article::with('headings.images')->get(); // Load relationships
        return response()->json([
            'message' => 'Articles retrieved successfully',
            'data' => $articles
        ]);

        

    }

    // Store a new article
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string',
            'headings' => 'required|array',
            'headings.*.title' => 'required|string',
            'headings.*.content' => 'required|string',
            'headings.*.images' => 'nullable|array',
            'headings.*.images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Create the article
        $article = Article::create(['title' => $request->title]);

        // Iterate through each heading and create them
        foreach ($request->headings as $headingData) {
            // Create heading for the article using the relationship
            $heading = $article->headings()->create([
                'title' => $headingData['title'],
                'content' => $headingData['content'],
            ]);

            // Save images for the heading if provided
            if (isset($headingData['images'])) {
                foreach ($headingData['images'] as $image) {
                    $path = $image->store('headings'); // Store image and get the path
                    $heading->images()->create(['image_path' => $path]); // Create image with the heading relationship
                }
            }
        }

        return response()->json([
            'message' => 'Article created successfully',
            'data' => $article->load('headings.images')
        ], 201);
    }

    // Get a single article by ID
    public function show($id)
    {
        $article = Article::with('headings.images')->findOrFail($id);
        return response()->json([
            'message' => 'Article retrieved successfully',
            'data' => $article
        ]);
    }

    //Search for a article
    public function search($title){
        $article = Article::where('title','like', '%'.$title.'%')->get();
        return response()->json([
            'message' => 'Article Searched Successfully',
            'data' => $articles
        ]);
    }

    // Update an existing article
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string',
            'headings' => 'required|array',
            'headings.*.title' => 'required|string',
            'headings.*.content' => 'required|string',
            'headings.*.images' => 'nullable|array',
            'headings.*.images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $article = Article::findOrFail($id);
        $article->title = $request->title;
        $article->save();

        // Clear existing headings
        $article->headings()->delete();

        // Recreate headings
        foreach ($request->headings as $headingData) {
            $heading = $article->headings()->create([
                'title' => $headingData['title'],
                'content' => $headingData['content'],
            ]);

            // Save images for the heading if provided
            if (isset($headingData['images'])) {
                foreach ($headingData['images'] as $image) {
                    $path = $image->store('headings'); // Store image and get the path
                    $heading->images()->create(['image_path' => $path]); // Create image with the heading relationship
                }
            }
        }

        return response()->json([
            'message' => 'Article updated successfully',
            'data' => $article->load('headings.images')
        ], 200);
    }

    // Delete an article by ID
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json([
            'message' => 'Article deleted successfully'
        ], 204);
    }
}
