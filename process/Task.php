<?php

namespace process;

use app\event\RuanyifengWeekly;
use app\event\ZhiHu;
use Webman\Event\Event;
use Workerman\Crontab\Crontab;

class Task
{
    public function onWorkerStart()
    {
        new Crontab('0 12 * * 5', function () {
            Event::dispatch(RuanyifengWeekly::alias, null);
        }); // 每周五中午12点执行
        new Crontab('*/2 * * * *', function () {
            Event::dispatch(ZhiHu::alias, null);
        }); // 每五分钟执行
    }
}