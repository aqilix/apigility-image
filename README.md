aqilixapi-image
===============

#Images API Handler
This module is purposed to handle image for an API (create/upload, read, update, delete). It use MySQL with Doctrine ORM. This module is extendable to use another database adapter.

Currently this module has support these resources with required OAuth2 Authentication

- POST  /v1.0/image
- GET   /v1.0/image/id
- PATCH /v1.0/image/id
- DEL   /v1.0/image/id

To retrieve the access token, you can use this resource `POST /oauth` by use some params:

- grant_type
- client_secret
- client_id
- username
- password

Dependencies
------------
- [doctrine/doctrine-orm-module](https://packagist.org/packages/doctrine/doctrine-orm-module)
- [zfcampus/zf-oauth2-doctrine](https://packagist.org/packages/zfcampus/zf-oauth2-doctrine)

Installation
------------
This is a ZF2/Apigility module, so to use it on your ZF2/Apigility project, need to add `repositories` and `require` on `composer.json`. 

```
  "require": {
    .
    .
    .
    "aqilixapi/image": "1.1"
  }
```

Run `composer update` then enable the module on `config/application.config.php`

```
return array(
    'modules' => array(
       .
       .
       .
       'AqilixAPI\\Image', 
    )
)
```


Configuration
-------------
Because of this module use `doctrine/doctrine-orm-module`, we just need to configure database credential using configuration file. I prepare the config file here `config/doctrine.local.php.dst`. Just copy this file to `config/autoload/doctrine.local.php` and change the `connection params` 

```
    'connection' => array(
        'orm_default' => array(
            'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOMySql\\Driver',
            'params' => array(
                'host'     => '127.0.0.1',
                'dbname'   => 'apigility',
                'user'     => 'apigility',
                'password' => 'apigility',
            ),
        ),
    ),
```

After that, we need to configure the `image path`, `thumbnail path`, `original file path` and `Asset Manager path`. The configuration file is here `config/aqilixapi.image.local.php.dist`. Copy this file to `config/autoload/aqilixapi.image.local.php` and adjust these configuration

```
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                'data/upload',
            ),
        ),
    ),
    'images' => array(
        'asset_manager_resolver_path' => 'data/upload',
        'target' => 'data/upload/images/img',
        'thumb_path' => 'data/upload/images/thumbs',
        'ori_path'   => 'data/upload/images/ori',
    ),

```

Make sure those paths are exists and writeable by `Web Server`, but if you just use `PHP built in web server` for development you don't need to change their permissions.

To enable `OAuth2` Authentication, just copy default configuration files `(config/oauth2.doctrine-orm.local.php.dist, config/oauth2.local.php.dist)` to `config/autoload/oauth2.doctrine-orm.local.php` and `config/autoload/oauth2.local.php`.


Database
--------
This module use a table called `image`. Currently it use `MySQL`, but you can change it based on your need easily as long as the database is supported by `Doctrine ORM`. If you have follow instructions above, it mean just remain creating the database table.

To do that just run this command from `app skeleton` working directory

```
vendor/bin/doctrine-module orm:schema-tool:create
```

Table will created and app ready to used.




