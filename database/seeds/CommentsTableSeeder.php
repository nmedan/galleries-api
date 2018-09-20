<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Gallery::all()->each(function (App\Gallery $gallery) {
            $gallery->comments()->saveMany(factory(App\Comment::class, 5)->make());
        });
    }
}
