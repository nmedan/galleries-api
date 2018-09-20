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
        $galleries = Gallery::with('images')->with('user')->get();
        return $galleries;
    }

    public function store(StoreGalleryRequest $request)
    {
        $gallery = Gallery::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth()->user()->id
        ]);      
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
        $comment = Comment::create([
            'content' => $request->content,
            'gallery_id' => $id,
            'user_id' => Auth()->user()->id
        ]);
    }

    public function deleteComment($id) {
        if (Auth()->check()) {
            Comment::destroy($id);
        }
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        if (Auth()->check()) {
            Gallery::destroy($id);
        }
    }
}
