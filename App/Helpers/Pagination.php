<?php

namespace App\Helpers;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

class Pagination {

    const MAX_PER_PAGE = 3;

    public function init($items)
    {
        $adapter = new ArrayAdapter($items);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta;
    }

    public function render($items, $params)
    {
        $pagerfanta = $this->init($items);
        $pagerfanta->setMaxPerPage(self::MAX_PER_PAGE);
        $page = $params['page'] ?? 1;
        unset($params['page']);
        $pagerfanta->setCurrentPage($page);

        $view = new TwitterBootstrap3View();
        $options = array('proximity' => 3);

        $routeGenerator = function($page) use ($params) {
            return '?page=' . $page . $this->generateRouteQuery($params);
        };
        
        $pagination = $view->render($pagerfanta, $routeGenerator, $options);

        return $pagination;
    }

    protected function generateRouteQuery($params) {
        $routeQuery = '';

        foreach ($params as $key => $value) {
            $routeQuery .= "&$key=$value";
        }

        return $routeQuery;
    }
}