<?php


namespace app\controller;


use support\Redis;
use Webman\Event\Event;

class ListController
{
    public function test()
    {
        Event::emit('zhihu', null);
        return json([
            'code' => 200,
            'msg'  => 'success'
        ]);
    }

    public function index()
    {
        $data = Redis::get('zhihu');
        $data = json_decode($data, true);
        return json($data);
    }
}