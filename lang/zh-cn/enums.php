<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

//use App\Repositories\Enums\ExampleEnum;
use Jcc\Jwt\Services\Enums\ResponseEnumsService;

return [
    'validations' => [
        'enum' => 'The value you have provided is not a valid enum instance.',
        'enum_value' => 'The value you have entered is invalid.',
        'enum_key' => 'The key you have entered is invalid.',
    ],

    // 响应状态码
    ResponseEnumsService::class => [
        // 成功
        ResponseEnumsService::HTTP_OK => '操作成功', // 自定义 HTTP 状态码返回消息

        // 业务操作成功
        ResponseEnumsService::SERVICE_REGISTER_SUCCESS => '注册成功',
        ResponseEnumsService::SERVICE_LOGIN_SUCCESS => '登录成功',

        // 客户端错误
        ResponseEnumsService::CLIENT_PARAMETER_ERROR => '参数错误',
        ResponseEnumsService::CLIENT_CREATED_ERROR => '数据已存在',
        ResponseEnumsService::CLIENT_DELETED_ERROR => '数据不存在',
        ResponseEnumsService::CLIENT_PARAMETER_DOT_EXIST_ERROR => '用户不存在',
        ResponseEnumsService::CLIENT_TO_QUICKLY_ERROR => '操作太频繁',
        ResponseEnumsService::CLIENT_CODE_ERROR => '验证码不正确',
        ResponseEnumsService::CLIENT_VALIDATION_ERROR => '账号或密码错误',

        // 服务端错误
        ResponseEnumsService::SYSTEM_ERROR => '服务器错误',
        ResponseEnumsService::SYSTEM_UNAVAILABLE => '服务器正在维护，暂不可用',

        // 业务操作失败：授权业务
        ResponseEnumsService::SERVICE_REGISTER_ERROR => '注册失败',
        ResponseEnumsService::SERVICE_LOGIN_ERROR => '登录失败',
    ],

//    // example
//    ExampleEnum::class => [
//        ExampleEnum::ADMINISTRATOR => '管理员',
//        ExampleEnum::SUPER_ADMINISTRATOR => '超级管理员',
//    ],
];
