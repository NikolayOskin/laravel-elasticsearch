<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $elastic = app(App\Search\Elastic::class);


    $instance = new App\Post;

    $items = $elastic->search([
        'index' => $instance->getSearchIndex(),
        'type' => $instance->getSearchType(),
        'body' => [
            'query' => [
                'multi_match' => [
                    'fields' => ['title', 'body'],
                    'query' => 'experiment',
                ],
            ],
            'highlight' => [
                'pre_tags' => ["<b>"],
                'post_tags' => ["</b>"],
                'fields' => [
                    'body' => new \stdClass()
                ],

            ],
        ],
    ]);
    //dd($items);
    $hits = array_pluck($items['hits']['hits'], '_source') ?: [];
    dd(App\Post::hydrate($hits));



    dd($hits);

    return view('welcome');
});
