<?php

use app\event\RuanyifengWeekly;
use app\event\Toutiao;
use app\event\ZhiHu;

$type = [
    ['id' => 1, 'title' => '社媒'],
    ['id' => 2, 'title' => '周刊'],
    ['id' => 3, 'title' => '新闻资讯'],
    ['id' => 4, 'title' => '短视频'],
    ['id' => 5, 'title' => 'IT科技'],
    ['id' => 6, 'title' => '体育游戏'],
    ['id' => 7, 'title' => '汽车资讯']
];
$category = [
    [
        'id'        => 1,
        'title'     => '知乎',
        'alias'     => ZhiHu::alias,
        'parent_id' => 1,
        'icon'      => 'icon-zhihu',
        'logo'      => '/logo/zhihu.svg'
    ],
    [
        'id'        => 2,
        'title'     => '今日头条',
        'alias'     => Toutiao::alias,
        'parent_id' => 1,
        'icon'      => 'icon-jinritoutiao',
        'logo'      => '/logo/toutiao.svg'
    ],
    [
        'id'        => 3,
        'title'     => '阮一峰周刊',
        'alias'     => RuanyifengWeekly::alias,
        'parent_id' => 2,
        'icon'      => '',
        'logo'      => ''
    ]
];
return [
    'type'     => $type,
    'category' => $category,
];