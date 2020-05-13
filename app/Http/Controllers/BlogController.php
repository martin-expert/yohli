<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $featured_posts = BlogPost::where('featured', 'yes')->with('categories', 'tags', 'media')->latest()->limit(5)->get();

        return view('blog.index')->with('featured_posts', $featured_posts);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tags()
    {
        $tags = BlogTag::limit(15)->get();

        return response()->json($tags);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        $categories = BlogCategory::limit(10)->get();

        return response()->json($categories);
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function featured_posts()
    {
        $featured_posts = BlogPost::where('featured', 'yes')->with('categories', 'tags', 'media')->latest()->limit(5)->get();

        return response()->json($featured_posts);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trending_posts()
    {
        $trending_posts = BlogPost::with('categories', 'tags', 'media')->orderBy('count', 'desc')->latest()->limit(5)->get();

        return response()->json($trending_posts);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function posts(Request $request)
    {
        $category = $request->category;
        $tag = $request->tag;

        $blog_posts = BlogPost::with('categories', 'tags', 'media')->latest()
            ->when(!empty($category), function ($query) use ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('id', $category)
                    ->orwhere('slug', $category);
                });
            })
            ->when(!empty($tag), function ($query) use ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('id', $tag)->orwhere('slug', $tag);
                });
            })
            ->paginate(5);

        // $blog_posts->appends(['category' => $category]);
        // $blog_posts->appends(['tag' => $tag]);

        return response()->json($blog_posts);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->with('categories', 'tags')
            ->first();

        $trending_posts = BlogPost::with('categories', 'tags', 'media')->orderBy('count', 'desc')->latest()->limit(5)->get();

        $categories = BlogCategory::limit(10)->get();

        $tags = BlogTag::limit(15)->get();

        return view('blog.show', compact(
            'post',
            'trending_posts',
            'categories',
            'tags'
        ));
    }
}
