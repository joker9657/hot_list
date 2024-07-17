<?php

namespace app\event;

use Carbon\Carbon;
use GuzzleHttp\Client;
use QL\QueryList;
use support\Redis;

class Weibo
{
    public const url = 'https://s.weibo.com/top/summary?cate=realtimehot';
    const userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';
    public const alias = 'weibo';

    public function update()
    {
        try {
            $host = 'https://s.weibo.com';

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
            $table = $ql->html($html)->find('table:eq(0)');

            $list = $table->find('tr:gt(1)')->map(function ($row) use ($host) {
                $title = $row->find('td:eq(1)>a')->text();
                $subtitle = $row->find('td:eq(1)>span')->text();
                $url = $host . $row->find('td:eq(1)>a')->attr('href');

                // 过滤掉包含中文的记录
                if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $subtitle)) {
                    return null;
                }
                return [
                    'title'    => $title,
                    'url'      => $url,
                    'subtitle' => (int)$subtitle
                ];
            })->filter()->values()->toArray();
            dump($list);
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