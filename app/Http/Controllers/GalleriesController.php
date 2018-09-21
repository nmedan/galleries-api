<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\StoreCommentRequest;
use Auth;
use App\Gallery;
use App\Image;
use App\Comment;

class GalleriesController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('images')->orderBy('created_at', 'desc')->with('user')->get();
        return $galleries;
    }

    public function store(StoreGalleryRequest $request)
    {
        $gallery = Gallery::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth()->user()->id
        ]);
        
        foreach($request->images as $img) {
            $i = (object)$img;
            $image = Image::create([
                'image_url' => $i->image_url,
                'gallery_id' => $gallery->id
            ]);
            $gallery->images()->save($image);
        }
    }

    public function show($id)
    {
        $gallery = Gallery::with('images', 'comments', 'user', 'comments.user')->find($id);
        return $gallery;
    }

    public function getByAuthor($id) 
    {
        $galleries = Gallery::where('user_id', $id)->with('images', 'user')->get();
        return $galleries;
    }

    public function getByUser() 
    {
        $user_id = Auth()->user()->id;      
        $galleries = Gallery::where('user_id', $user_id)->with('images', 'user')->get();
        return $galleries;
    }

    public function postComment(StoreCommentRequest $request, $id) {
        if (Auth()->check()) {
            $comment = Comment::create([
                'content' => $request->content,
                'gallery_id' => $id,
                'user_id' => Auth()->user()->id
            ]);
        }
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        if (Auth()->user()->id == $comment->user_id) {
            Comment::destroy($id);
        }
    }

    public function edit($id)
    {
        $gallery = Gallery::with('images', 'comments', 'user', 'comments.user')->findOrFail($id);
        if (Auth()->user()->id == $gallery->user_id) {
           return $gallery;
        }
        return null;
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->update([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth()->user()->id
        ]);

        $gallery->images()->delete();
        foreach($request->images as $img) {
            $i = (object)$img;
            $image = Image::create([
                'image_url' => $i->image_url,
                'gallery_id' => $gallery->id
            ]);
            $gallery->images()->save($image);
        }

    }
    
    public function filter($term) 
    {
        return Gallery::with('images', 'comments', 'user', 'comments.user')->where('name', 'LIKE',  '%'.$term.'%')->get();
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        if (Auth()->user()->id == $gallery->user_id) {
            Gallery::destroy($id);
        }
    }
}
