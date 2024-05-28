<?php

namespace app\event;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use support\Redis;

class ZhiHu
{
    //知乎的热榜接口可以直接请求
    public const url = 'https://www.zhihu.com/api/v4/creators/rank/hot?domain=0&period=hour&limit=30';

    public const userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';

    public const alias = 'zhihu';

    /**
     * 更新知乎热榜
     * @return void
     */
    public function update(): void
    {
        try {
            $client = new Client();
            $res = json_decode($client->get(self::url, [
                'headers' => [
                    //模拟浏览器请求
                    'user-agent' => self::userAgent
                ]
            ])->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            $insertData = [];
            foreach ($res['data'] as $item) {
                $title = $item['question']['title'];
                $url = $item['question']['url'];
                $subtitle = $item['reaction']['pv'];
                $insertData[] = [
                    'title'       => $title,
                    'url'         => $url,
                    'subtitle'    => $subtitle,
                    'date'        => Carbon::now()->toDateString(),
                    'create_time' => Carbon::now()->toDateTimeString()
                ];
            }

            if (empty($insertData)) return;
            $list = json_encode($insertData, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            dump($list);
            //redis缓存 记录json数据和更新时间
            Redis::set(self::alias, $list);
            Redis::set(self::alias . '-time', Carbon::now()->toDateTimeString());

            dump(date('Y-m-d H:i:s') . '更新' . self::alias . '成功');
        } catch (GuzzleException|\Exception $exception) {
            dump('更新' . self::alias . '异常：' . $exception->getMessage());
            dump($exception);
        }
    }
}