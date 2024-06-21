<?php


namespace app\controller;


use support\Redis;
use support\Request;
use support\Response;

class ListController
{
    /**
     * @description hot list data
     * @param Request $request
     * @param string $alias
     * @return Response
     */
    public function index(Request $request, string $alias): Response
    {
        try {
            if (!$alias) {
                throw new \RuntimeException('param error');
            }
            return json([
                'code' => 200,
                'msg'  => 'success',
                'data' => json_decode(Redis::get($alias), true)
            ]);
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            return json([
                'code' => 500,
                'msg'  => $e->getMessage()
            ]);
        }
    }
}