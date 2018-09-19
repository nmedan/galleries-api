<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Gallery;
use App\Image;

class GalleriesController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('images')->with('user')->get();
        return $galleries;
    }

    public function store(Request $request)
    {
        
    }

    public function show($id)
    {
        $gallery = Gallery::with('images')->find($id);
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

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
