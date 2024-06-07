<?php


namespace app\controller;


use support\Redis;
use Webman\Event\Event;

class ListController
{
    public function test()
    {
        try {

            Event::dispatch('ruanyifeng-weekly', null);
            return json([
                'code' => 200,
                'msg'  => 'success'
            ]);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    public function index()
    {
        $data = Redis::get('ruanyifeng-weekly');
        $data = json_decode($data, true);
        return json($data);
    }
}