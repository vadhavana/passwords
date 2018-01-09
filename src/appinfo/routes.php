<?php

namespace OCA\Passwords\AppInfo;

$application = new Application();

/** @var $this \OC\Route\CachingRouter */
$application->registerRoutes($this, [
    'resources' => [
        'legacy_category_api' => ['url' => '/api/0.1/categories'],
        'legacy_password_api' => ['url' => '/api/0.1/passwords'],
        'legacy_version_api' => ['url' => '/api/0.1/version']
    ],
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'admin_settings#set', 'url' => '/admin/set', 'verb' => 'POST'],
        ['name' => 'admin_settings#cache', 'url' => '/admin/cache', 'verb' => 'POST'],

        ['name' => 'authorisation_api#info', 'url' => '/api/1.0/authorisation/info', 'verb' => 'GET'],
        ['name' => 'authorisation_api#login', 'url' => '/api/1.0/authorisation/login', 'verb' => 'POST'],
        ['name' => 'authorisation_api#logout', 'url' => '/api/1.0/authorisation/logout', 'verb' => 'POST'],

        ['name' => 'password_api#list', 'url' => '/api/1.0/password/list', 'verb' => 'GET'],
        ['name' => 'password_api#list', 'url' => '/api/1.0/password/list', 'verb' => 'POST', 'postfix' => 'POST'],
        ['name' => 'password_api#show', 'url' => '/api/1.0/password/show', 'verb' => 'POST'],
        ['name' => 'password_api#find', 'url' => '/api/1.0/password/find', 'verb' => 'POST'],
        ['name' => 'password_api#create', 'url' => '/api/1.0/password/create', 'verb' => 'POST'],
        ['name' => 'password_api#update', 'url' => '/api/1.0/password/update', 'verb' => 'PATCH'],
        ['name' => 'password_api#delete', 'url' => '/api/1.0/password/delete', 'verb' => 'DELETE'],
        ['name' => 'password_api#restore', 'url' => '/api/1.0/password/restore', 'verb' => 'PATCH'],

        ['name' => 'folder_api#list', 'url' => '/api/1.0/folder/list', 'verb' => 'GET'],
        ['name' => 'folder_api#list', 'url' => '/api/1.0/folder/list', 'verb' => 'POST', 'postfix' => 'POST'],
        ['name' => 'folder_api#show', 'url' => '/api/1.0/folder/show', 'verb' => 'POST'],
        ['name' => 'folder_api#find', 'url' => '/api/1.0/folder/find', 'verb' => 'POST'],
        ['name' => 'folder_api#create', 'url' => '/api/1.0/folder/create', 'verb' => 'POST'],
        ['name' => 'folder_api#update', 'url' => '/api/1.0/folder/update', 'verb' => 'PATCH'],
        ['name' => 'folder_api#delete', 'url' => '/api/1.0/folder/delete', 'verb' => 'DELETE'],
        ['name' => 'folder_api#restore', 'url' => '/api/1.0/folder/restore', 'verb' => 'PATCH'],

        ['name' => 'tag_api#list', 'url' => '/api/1.0/tag/list', 'verb' => 'GET'],
        ['name' => 'tag_api#list', 'url' => '/api/1.0/tag/list', 'verb' => 'POST', 'postfix' => 'POST'],
        ['name' => 'tag_api#show', 'url' => '/api/1.0/tag/show', 'verb' => 'POST'],
        ['name' => 'tag_api#find', 'url' => '/api/1.0/tag/find', 'verb' => 'POST'],
        ['name' => 'tag_api#create', 'url' => '/api/1.0/tag/create', 'verb' => 'POST'],
        ['name' => 'tag_api#update', 'url' => '/api/1.0/tag/update', 'verb' => 'PATCH'],
        ['name' => 'tag_api#delete', 'url' => '/api/1.0/tag/delete', 'verb' => 'DELETE'],
        ['name' => 'tag_api#restore', 'url' => '/api/1.0/tag/restore', 'verb' => 'PATCH'],

        ['name' => 'share_api#info', 'url' => '/api/1.0/share/info', 'verb' => 'GET'],
        ['name' => 'share_api#list', 'url' => '/api/1.0/share/list', 'verb' => 'GET'],
        ['name' => 'share_api#list', 'url' => '/api/1.0/share/list', 'verb' => 'POST', 'postfix' => 'POST'],
        ['name' => 'share_api#show', 'url' => '/api/1.0/share/show', 'verb' => 'POST'],
        ['name' => 'share_api#find', 'url' => '/api/1.0/share/find', 'verb' => 'POST'],
        ['name' => 'share_api#types', 'url' => '/api/1.0/share/types', 'verb' => 'GET'],
        ['name' => 'share_api#create', 'url' => '/api/1.0/share/create', 'verb' => 'POST'],
        ['name' => 'share_api#update', 'url' => '/api/1.0/share/update', 'verb' => 'PATCH'],
        ['name' => 'share_api#delete', 'url' => '/api/1.0/share/delete', 'verb' => 'DELETE'],
        ['name' => 'share_api#partners', 'url' => '/api/1.0/share/partners', 'verb' => 'GET'],
        ['name' => 'share_api#partners', 'url' => '/api/1.0/share/partners', 'verb' => 'POST', 'postfix' => 'POST'],

        ['name' => 'client_api#list', 'url' => '/api/1.0/client/list', 'verb' => 'GET'],
        ['name' => 'client_api#show', 'url' => '/api/1.0/client/show', 'verb' => 'POST'],
        ['name' => 'client_api#create', 'url' => '/api/1.0/client/create', 'verb' => 'POST'],
        ['name' => 'client_api#update', 'url' => '/api/1.0/client/update', 'verb' => 'PATCH'],
        ['name' => 'client_api#delete', 'url' => '/api/1.0/client/delete', 'verb' => 'DELETE'],

        ['name' => 'settings_api#get', 'url' => '/api/1.0/setting/get', 'verb' => 'POST'],
        ['name' => 'settings_api#set', 'url' => '/api/1.0/setting/set', 'verb' => 'POST'],
        ['name' => 'settings_api#list', 'url' => '/api/1.0/setting/list', 'verb' => 'GET'],
        ['name' => 'settings_api#reset', 'url' => '/api/1.0/setting/reset', 'verb' => 'POST'],

        ['name' => 'service_api#generate_password', 'url' => '/api/1.0/service/password', 'verb' => 'GET'],
        [
            'name'    => 'service_api#generate_password',
            'url'     => '/api/1.0/service/password',
            'verb'    => 'POST',
            'postfix' => 'POST'
        ],
        [
            'name'     => 'service_api#get_favicon',
            'url'      => '/api/1.0/service/favicon/{domain}/{size}',
            'verb'     => 'GET',
            'defaults' => ['domain' => '', 'size' => 32]
        ],
        [
            'name'     => 'service_api#get_avatar',
            'url'      => '/api/1.0/service/avatar/{user}/{size}',
            'verb'     => 'GET',
            'defaults' => ['user' => '', 'size' => 32]
        ],
        [
            'name'     => 'service_api#get_preview',
            'url'      => '/api/1.0/service/image/{domain}/{view}/{width}/{height}',
            'verb'     => 'GET',
            'defaults' => ['domain' => '', 'view' => 'desktop', 'width' => 550, 'height' => 0]
        ],
        ['name' => 'service_api#coffee', 'url' => '/api/1.0/service/coffee', 'verb' => 'GET'],
    ]
]);
