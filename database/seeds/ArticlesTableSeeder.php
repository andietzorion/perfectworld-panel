<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( !DB::table('pweb_articles')->where('title', 'Welcome' )->exists() )
        {
            DB::table('pweb_articles')->insert([
                'author' => 1024,
                'title' => 'Welcome',
                'content' => '<p>Congratulations on successfully installing your PW Web! Browse around and get a feel for everything! If you have any questions or issues please don\'t hesitate to post them on <a href="https://gamemaster.id/" target="_blank">Game Master ID</a> or ask me by <a href="https://www.facebook.com/wahyu.suhandi" target="_blank">Facebook</a>, or <a href="https://m.me/wahyu.suhandi" target="_blank">Messenger</a>.</p>',
                'category' => 'other',
                'slug' => 'welcome',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
    }
}
