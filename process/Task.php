<?php

namespace process;

use app\event\Hupu;
use app\event\LaravelWeekly;
use app\event\Onelink;
use app\event\RuanyifengWeekly;
use app\event\Toutiao;
use app\event\Weibo;
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
        new Crontab('0 18 * * 6', function () {
            Event::dispatch(Onelink::alias, null);
        }); // 每周六下午6点执行
        new Crontab('0 12 * * 1', function () {
            Event::dispatch(LaravelWeekly::alias, null);
        }); // 每周一中午12点执行
        new Crontab('*/4 * * * *', function () {
            Event::dispatch(Weibo::alias, null);
        }); // 每四分钟执行
        new Crontab('*/5 * * * *', function () {
            Event::dispatch(ZhiHu::alias, null);
        }); // 每五分钟执行
        new Crontab('*/6 * * * *', function () {
            Event::dispatch(Toutiao::alias, null);
        }); // 每六分钟执行
        new Crontab('*/6 * * * *', function () {
            Event::dispatch(Hupu::alias, null);
        }); // 每六分钟执行
    }
}