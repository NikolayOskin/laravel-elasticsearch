<?php

namespace App\Http\Controllers;

use App\Services\ElasticsearchService;
use Illuminate\Http\Request;
use App\Search\Elastic;
use App\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function searchPosts(Request $request, Post $post)
    {
        $query = $request->get('q');
        if (empty($query)) {
            return redirect(route('index'));
        }
        $size = 8;
        $from = ($request->get('page', 1) - 1) * $size;
        $elastic = app(Elastic::class);

        $elasticService = new ElasticsearchService($post, $elastic, $query, $size, $from);
        $results = $elasticService->searchWithHighlight();
        $totalResults = $elasticService->getResultsAmount();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator($results, $totalResults, $size, $currentPage,['path' => url('search')]);
        return view('search', compact('results','paginator', 'query'));
    }
}
