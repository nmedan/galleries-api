<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Database\Eloquent;
use Auth;
use App\Gallery;
use App\Image;
use App\Comment;

class GalleriesController extends Controller
{
    public function index($currentPage)
    {
		Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        
		$galleries = Gallery::with('images', 'user')->orderBy('created_at', 'desc')->paginate(2);	
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

    public function getByAuthor($id, $currentPage) 
    {
		Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
		
        $galleries = Gallery::where('user_id', $id)->orderBy('created_at', 'desc')->with('images', 'user')->paginate(2);
        return $galleries;
    }

    public function getByUser($currentPage) 
    {
		Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
		
        $user_id = Auth()->user()->id;      
        $galleries = Gallery::where('user_id', $user_id)->orderBy('created_at', 'desc')->with('images', 'user')->paginate(2);
        return $galleries;
    }
	
	public function filterAllGalleries($currentPage, $term)
    {
		Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        
		$galleries = Gallery::with('images', 'user')->where('name', 'LIKE', '%'.$term.'%')->orderBy('created_at', 'desc')->paginate(2);	
        return $galleries;
    }
	
	public function filterAuthorsGalleries($id, $currentPage, $term) 
    {
		Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
		   	
        $galleries = Gallery::with('images', 'user')->where('user_id', $id)->where('name', 'LIKE', '%'.$term.'%')->orderBy('created_at', 'desc')->paginate(2);
        return $galleries;
    }
	
	public function filterUsersGalleries($currentPage, $term) 
    {
		Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
		
		$user_id = Auth()->user()->id; 
        $galleries = Gallery::with('images', 'user')->where('user_id', $user_id)->where('name', 'LIKE', '%'.$term.'%')->orderBy('created_at', 'desc')->paginate(2);
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

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        if (Auth()->user()->id == $gallery->user_id) {
            Gallery::destroy($id);
        }
    }
}
