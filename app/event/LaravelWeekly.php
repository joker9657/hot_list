<?php

namespace app\event;

use Carbon\Carbon;
use GuzzleHttp\Client;
use QL\QueryList;
use support\Redis;

class LaravelWeekly
{
    public const url = 'https://learnku.com/laravel/weekly';
    const userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';
    public const alias = 'laravel-weekly';

    public function update()
    {
        try {
            $client = new Client([
                //允许重定向
                'allow_redirects' => true,
                'verify'          => false
            ]);

            $html = $client->get(self::url, [
                'headers' => [
                    //模拟浏览器请求
                    'user-agent' => self::userAgent,
                ]
            ])->getBody()->getContents();

            $ql = new QueryList();

            //解析html
            $table = $ql->html($html)->find('.centered .list');

            $list = $table->find('a')->map(function ($row) {
                return [
                    'title'    => $row->find('.pull-left')->text(),
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