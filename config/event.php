<?php

use app\event\Hupu;
use app\event\LaravelWeekly;
use app\event\Onelink;
use app\event\RuanyifengWeekly;
use app\event\Toutiao;
use app\event\Weibo;
use app\event\ZhiHu;

return [
    //知乎热榜
    ZhiHu::alias            => [
        [ZhiHu::class, 'update'],
    ],
    // 阮一峰周刊
    RuanyifengWeekly::alias => [
        [RuanyifengWeekly::class, 'update']
    ],
    //头条热榜
    Toutiao::alias          => [
        [Toutiao::class, 'update']
    ],
    //微博热搜
    Weibo::alias            => [
        [Weibo::class, 'update']
    ],
    //虎扑步行街
    Hupu::alias             => [
        [Hupu::class, 'update']
    ],
    // Laravel 周刊
    'laravel-weekly'        => [
        [LaravelWeekly::class, 'update']
    ],
    // 1link 周刊
    '1link-weekly'        => [
        [Onelink::class, 'update']
    ],
];
