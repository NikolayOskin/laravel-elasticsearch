<?php

namespace App\Services;

class ElasticsearchService
{
    protected $model;
    protected $elastic;
    protected $query;
    protected $size;
    protected $from;
    protected $foundItems;
    protected $foundItemsAmount;

    public function __construct($model, $elastic, $query, int $size, int $from)
    {
        $this->model = $model;
        $this->elastic = $elastic;
        $this->query = $query;
        $this->size = $size;
        $this->from = $from;
    }

    public function getResultsWithHighlight():array
    {
        $this->searchWithHighlight();

        $results = array_pluck($this->foundItems['hits']['hits'], '_source') ?: [];
        $highlights = array_pluck($this->foundItems['hits']['hits'], 'highlight') ?: [];

        foreach ($results as $k => &$v) {
            $v['highlight'] = $highlights[$k]['body'][0];
        }
        return $results;
    }

    public function searchWithHighlight()
    {
        $items = $this->elastic->search([
            'index' => $this->model->getSearchIndex(),
            'type' => $this->model->getSearchType(),
            'body' => [
                "from" => $this->from,
                "size" => $this->size,
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'body'],
                        'query' => $this->query,
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
        $this->foundItems = $items;
        $this->foundItemsAmount = $items['hits']['total'];
        return $this;
    }

    public function getResultsAmount():int
    {
        if (!empty($this->foundItemsAmount)) {
            return $this->foundItemsAmount;
        }
        $this->searchWithHighlight();
        return $this->foundItemsAmount;
    }

}