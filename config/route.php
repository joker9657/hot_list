<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use app\controller\ListController;
use Webman\Route;

Route::group('/api', function () {
    Route::get('/category', [ListController::class, 'category']);
    Route::get('/hot/{alias}', [ListController::class, 'index']);
//    Route::get('/test', function () {
//        \Webman\Event\Event::emit('hupu', null);
//    });
});

// 给所有OPTIONS请求设置跨域
Route::options('[{path:.+}]', function () {
    return response('');
});
//自定义404
Route::fallback(fn() => json(['code' => 405, 'msg' => 'not found']));
Route::disableDefaultRoute();



