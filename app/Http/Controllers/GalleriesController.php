<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
