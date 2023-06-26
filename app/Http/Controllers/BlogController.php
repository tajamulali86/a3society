<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

use Illuminate\Support\Facades\Storage;


class BlogController extends Controller
{
    public function index()
    {
        // Display a list of blog posts
        $posts = Blog::all();
        return view('blog-posts.index', compact('posts'));
    }

    public function create()
    {
        // Show the form to create a blog post
        return view('blog-posts.create');
    }

    public function store(Request $request)
    {
        // Store the blog post with the associated image
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = new Blog();
        $post->title = $data['title'];
        $post->content = $data['content'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('blog-posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('blog-posts.index')->with('success', 'Blog post created successfully!');
    }

    public function show(Blog $blog)
    {
        // Show details of a specific blog post
        return view('blog-posts.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        // Show the form to edit a blog post
        return view('blog-posts.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        // Update the blog post details
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog->title = $data['title'];
        $blog->content = $data['content'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('blog-posts', 'public');
            $blog->image = $path;
        }

        $blog->save();

        return redirect()->route('blog-posts.index')->with('success', 'Blog post updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        // Delete the blog post and associated image
        Storage::disk('public')->delete($blog->image);
        $blog->delete();

        return redirect()->route('blog-posts.index')->with('success', 'Blog post deleted successfully!');
    }
}
