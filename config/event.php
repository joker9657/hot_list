<?php

use app\event\ZhiHu;

return [
    //知乎热榜
    ZhiHu::alias       => [
        [ZhiHu::class, 'update'],
    ],
];
