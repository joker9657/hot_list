<?php

namespace app\event;

use Carbon\Carbon;
use QL\QueryList;
use support\Redis;

class RuanyifengWeekly
{
    public const url = 'https://www.ruanyifeng.com/blog/weekly/';

    public const userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';

    public const alias = 'ruanyifeng-weekly';

    public function update()
    {
        // 获取阮一峰周刊 module-content 第一个ul下面的全部li内容
        try {
            $list = QueryList::get(self::url)
                             ->find('#alpha ul:first li')
                             ->map(function ($item) {
                                 $title = $item->find('a')->html();
                                 $url = $item->find('a')->attr('href');
                                 return [
                                     'title'    => str_replace('[email protected]', '', $title),
                                     'url'      => $url,
                                     'subtitle' => 0,
                                 ];
                             });
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