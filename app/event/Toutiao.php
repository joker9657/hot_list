<?php

namespace app\event;

use Carbon\Carbon;
use GuzzleHttp\Client;
use support\Redis;

class Toutiao
{
    public const url = 'https://www.toutiao.com/hot-event/hot-board/?origin=toutiao_pc';

    public const userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';

    public const alias = 'toutiao';

    /**
     * 更新知乎热榜
     * @return void
     */
    public function update(): void
    {
        try {
            $host = 'https://www.toutiao.com/trending/';
            $client = new Client();
            $res = json_decode($client->get(self::url, [
                'headers' => [
                    //模拟浏览器请求
                    'user-agent' => self::userAgent
                ]
            ])->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            $insertData = [];
            foreach ($res['data'] as $item) {
                $title = $item['Title'];
                $url = $host . $item['ClusterIdStr'];
                $subtitle = $item['HotValue'];
                $insertData[] = [
                    'title'    => $title,
                    'url'      => $url,
                    'subtitle' => (int)$subtitle
                ];
            }
            if (empty($insertData)) return;
            $list = json_encode($insertData, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            //redis缓存 记录json数据和更新时间
            Redis::set(self::alias, $list);
            Redis::set(self::alias . '-time', Carbon::now()->toDateTimeString());

            dump(date('Y-m-d H:i:s') . '更新' . self::alias . '成功');
        } catch (\Throwable $e) {
            dump('更新' . self::alias . '异常：' . $e->getMessage());
        }
    }
}