<?php

namespace app\event;

use Carbon\Carbon;
use GuzzleHttp\Client;
use QL\QueryList;
use support\Redis;

class Hupu
{
    public const url = 'https://www.hupu.com/';
    const userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';
    public const alias = 'hupu';

    public function update()
    {
        try {
            $client = new Client([
                //允许重定向
                'allow_redirects' => true,
            ]);

            $html = $client->get(self::url, [
                'headers' => [
                    //模拟浏览器请求
                    'user-agent' => self::userAgent,
                    //cookie
                    'cookie'     => 'SUB=_2AkMUvoH0f8NxqwFRmP4SxWrnb451yQzEieKi4nAvJRMxHRl-yT9jqh1YtRB6Pz6vG53IuuNcVmtx7SWagTBey1bXoRF8; SUBP=0033WrSXqPxfM72-Ws9jqgMF55529P9D9WWir9y7UWdySP8OAUxCzbNI'
                ]
            ])->getBody()->getContents();

            $ql = new QueryList();

            //解析html
            $table = $ql->html($html)->find('.itemListA:eq(2)');

            $list = $table->find('a')->map(function ($row) {
                return [
                    'title'    => $row->find('.hot-title')->text(),
                    'url'      => $row->attr('href'),
                    'subtitle' => 0
                ];
            })->filter()->values()->toArray();
            $list = json_encode($list, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            Redis::set(self::alias, $list);
            Redis::set(self::alias . '-time', Carbon::now()->toDateTimeString());
            dump(date('Y-m-d H:i:s') . '更新' . self::alias . '成功');
        } catch (\Throwable $e) {
            dump('更新' . self::alias . '异常：' . $e->getMessage());
            dump($e->getMessage());
        }
    }
}