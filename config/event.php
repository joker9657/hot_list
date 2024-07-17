<?php

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
    ]
];
