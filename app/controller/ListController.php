<?php


namespace app\controller;


use RuntimeException;
use support\Redis;
use support\Response;
use Throwable;

class ListController
{
    /**
     * @description hot list data
     * @param string $alias
     * @return Response
     */
    public function index(string $alias): Response
    {
        try {
            if (!$alias) {
                throw new RuntimeException('param error');
            }
            $category = config('category')['category'];
            $haystack = array_column($category, 'alias');
            $key = array_search($alias, $haystack);
            return json([
                'code' => 200,
                'msg'  => 'success',
                'data' => [
                    'list'  => json_decode(Redis::get($alias), true),
                    'time'  => Redis::get($alias . '-time'),
                    'title' => $category[$key]['title'] ?? ''
                ]
            ]);
        } catch (Throwable $e) {
            return json([
                'code' => 500,
                'msg'  => $e->getMessage()
            ]);
        }
    }

    /**
     * @description category list data
     * @return Response
     */
    public function category(): Response
    {
        try {
            return json([
                'code' => 200,
                'msg'  => 'success',
                'data' => config('category')
            ]);
        } catch (Throwable $e) {
            return json([
                'code' => 500,
                'msg'  => $e->getMessage()
            ]);
        }
    }
}