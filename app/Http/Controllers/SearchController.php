<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Search\Elastic;
use App\Post;



class SearchController extends Controller
{
    public function searchPosts(Request $request)
    {
        $query = $request->input('q');

        $elastic = app(Elastic::class);

        $instance = new Post;

        $items = $elastic->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'body'],
                        'query' => $query,
                        'fuzziness' => 'AUTO',
                    ],
                ],
                'highlight' => [
                    'fragment_size' => '200',
                    'pre_tags' => ["<b>"],
                    'post_tags' => ["</b>"],
                    'fields' => ['body' => new \stdClass()],
                ],
            ],
        ]);

        $results = array_pluck($items['hits']['hits'], '_source') ?: [];
        $highlights = array_pluck($items['hits']['hits'], 'highlight') ?: [];

        foreach ($results as $k => &$v) {
            $v['highlight'] = $highlights[$k]['body'][0];
        }
        return view('search', compact('results'));
    }
}
