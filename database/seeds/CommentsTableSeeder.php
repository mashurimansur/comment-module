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
        $comments = [
            [
                'user_id' => 32,
                'comment' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, facere explicabo, impedit culpa libero quam nostrum cupiditate illum, error a maxime dolore ipsum blanditiis. Quas cupiditate voluptatibus excepturi quia velit!',
                'count_comment_likes' => 0,
                'module_unit_id' => 'idberita',
                'code_unit_id' => 2
            ],[
                'user_id' => 100,
                'comment' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'count_comment_likes' => 0,
                'module_unit_id' => 'idberita',
                'code_unit_id' => 2
            ],[
                'user_id' => 167,
                'comment' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'count_comment_likes' => 0,
                'module_unit_id' => 'idberita',
                'code_unit_id' => 2
            ], [
                'user_id' => 168,
                'comment' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'count_comment_likes' => 0,
                'module_unit_id' => 'idberita',
                'code_unit_id' => 2
            ]
        ];

        $treepath = [
            [
                'ancestor' => 1,
                'offspring' => 1,
                'depth' => 0
            ], [
                'ancestor' => 1,
                'offspring' => 2,
                'depth' => 1
            ], [
                'ancestor' => 2,
                'offspring' => 3,
                'depth' => 2
            ], [
                'ancestor' => 1,
                'offspring' => 4,
                'depth' => 1   
            ]
        ];


        try {
            DB::table('comments')->insert($comments);
            DB::table('treepath')->insert($treepath);
        } catch (\Exception $exception){
            return $exception;
        }
    }
}
