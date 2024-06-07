<?php

use app\event\RuanyifengWeekly;
use app\event\ZhiHu;

return [
    //知乎热榜
    ZhiHu::alias            => [
        [ZhiHu::class, 'update'],
    ],
    // 阮一峰周刊
    RuanyifengWeekly::alias => [
        [RuanyifengWeekly::class, 'update']
    ]
];
