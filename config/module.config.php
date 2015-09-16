<?php
return array(
    'router' => array(
        'routes' => array(
            'image.rest.image' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/v1.0/image[/:image_id]',
                    'defaults' => array(
                        'controller' => 'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller',
                    ),
                ),
            ),
            'image.rest.images' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/v1.0/images',
                    'defaults' => array(
                        'controller' => 'AqilixAPI\\Image\\V1\\Rest\\Images\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'hydrators' => array(
        'factories' => array(
            'AqilixAPI\\Image\\Entity\\Hydrator' => 'AqilixAPI\\Image\\Service\\Factory\DoctrineObjectHydratorFactory',
        ),
        'shared' => array(
            'AqilixAPI\\Image\\Entity\\Hydrator' => true
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'AqilixAPI\\Image\\Mapper\\Image'  => 'AqilixAPI\\Image\\Mapper\\Adapter\\DoctrineORMImage',
            'AqilixAPI\\Image\\Mapper\\User'   => 'AqilixAPI\\Image\\Mapper\\Adapter\\DoctrineORMUser',
            'AqilixAPI\\Image\\Service\\Image' => 'AqilixAPI\\Image\\Service\\Image',
            'AqilixAPI\\Image\\SharedEventListener' => 'AqilixAPI\\Image\\Service\\SharedEventListener',
            'AqilixAPI\\Image\\Authorization\\AclImageListener'    =>
                'AqilixAPI\\Image\\Authorization\\AclImageListener',
            'AqilixAPI\\Image\\Authorization\\AclScopeListener' =>
                'AqilixAPI\\Image\\Authorization\\AclScopeListener',
            'AqilixAPI\\Image\\V1\\Rest\\Image\\ImageResource'   =>
                'AqilixAPI\\Image\\V1\\Rest\\Image\\ImageResource',
            'AqilixAPI\\Image\\V1\\Rest\\Images\\ImagesResource' =>
                'AqilixAPI\\Image\\V1\\Rest\\Images\\ImagesResource',
            'AqilixAPI\\Image\\Stdlib\\Hydrator\\Strategy\\AssetManagerResolverStrategy' =>
                'AqilixAPI\\Image\\Stdlib\\Hydrator\\Strategy\\AssetManagerResolverStrategy'
        ),
        'factories' => array(
            'image.authenticated.user' => 'AqilixAPI\\Image\\Service\\Factory\\AuthUserFactory',
            'image.requested.image'    => 'AqilixAPI\\Image\\Service\\Factory\\RequestedImageFactory'
        ),
        'aliases' => array(
            'ZF\OAuth2\Provider\UserId' => 'ZF\OAuth2\Provider\UserId\AuthenticationService',
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'image.rest.image',
            1 => 'image.rest.images',
        ),
    ),
    'zf-rest' => array(
        'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller' => array(
            'listener' => 'AqilixAPI\\Image\\V1\\Rest\\Image\\ImageResource',
            'route_name' => 'image.rest.image',
            'route_identifier_name' => 'image_id',
            'collection_name' => 'image',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'AqilixAPI\\Image\\Entity\\Image',
            'collection_class' => 'AqilixAPI\\Image\\V1\\Rest\\Image\\ImageCollection',
            'service_name' => 'AqilixAPI\\Image',
        ),
        'AqilixAPI\\Image\\V1\\Rest\\Images\\Controller' => array(
            'listener' => 'AqilixAPI\\Image\\V1\\Rest\\Images\\ImagesResource',
            'route_name' => 'image.rest.images',
            'route_identifier_name' => 'images_id',
            'collection_name' => 'images',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(
               0 => 'page'
            ),
            'page_size' => 5,
            'page_size_param' => null,
            'entity_class' => 'AqilixAPI\\Image\\V1\\Rest\\Images\\ImagesEntity',
            'collection_class' => 'AqilixAPI\\Image\\V1\\Rest\\Images\\ImagesCollection',
            'service_name' => 'AqilixAPI\\Images',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller' => 'HalJson',
            'AqilixAPI\\Image\\V1\\Rest\\Images\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller' => array(
                0 => 'application/vnd.image.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'AqilixAPI\\Image\\V1\\Rest\\Images\\Controller' => array(
                0 => 'application/vnd.image.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller' => array(
                0 => 'application/vnd.image.v1+json',
                1 => 'application/json',
                2 => 'multipart/form-data',
                3 => 'image/jpeg',
                4 => 'image/png',
                5 => 'image/jpg',
            ),
            'AqilixAPI\\Image\\V1\\Rest\\Images\\Controller' => array(
                0 => 'application/vnd.image.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'AqilixAPI\\Image\\Entity\\Image' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'image.rest.image',
                'route_identifier_name' => 'image_id',
                'hydrator' => 'AqilixAPI\\Image\\Entity\\Hydrator',
            ),
            'AqilixAPI\\Image\\V1\\Rest\\Images\\ImagesCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'image.rest.images',
                'route_identifier_name' => 'images_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller' => array(
            'input_filter' => 'AqilixAPI\\Image\\V1\\Rest\\Image\\Validator',
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'AqilixAPI\\Image\\V1\\Rest\\Image\\Controller' => array(
                'entity' => array(
                    'GET' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ),
                'collection' => array(
                    'POST' => true,
                )
            ),
            'AqilixAPI\\Image\\V1\\Rest\\Images\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                ),
            ),
        ),
    ),
    'input_filter_specs' => array(
        'AqilixAPI\\Image\\V1\\Rest\\Image\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\NotEmpty',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alnum',
                        'options' => array(
                            'allowwhitespace' => true,
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'description',
                'description' => 'AqilixAPI\\Image Description',
                'error_message' => 'Description should be filled',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\File\\Extension',
                        'options' => array(
                            0 => 'jpg',
                            1 => 'png',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'image',
                'description' => 'AqilixAPI\\Image File',
                'type' => 'Zend\\InputFilter\\FileInput',
                'error_message' => 'File should be uploaded',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'image_db_driver' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\YamlDriver',
                'paths' => array(
                    0 => __DIR__ . '/entity',
                ),
                'cache' => 'array',
            ),
            'orm_default' => array(
                'drivers' => array(
                    'AqilixAPI\\Image\\Entity' => 'image_db_driver',
                ),
            ),
        ),
    ),
    'data-fixture' => array(
        'fixtures' => __DIR__ . '/../src/AqilixAPI/Image/Fixture'
    ),
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
    'authorization' => array(
        'scopes' => array(
            'post' => array(
                // 'resource' => 'AqilixAPI\Image\V1\Rest\Image\Controller::collection',
                // 'method' => 'POST',
            ),
            'update' => array(
                // 'resource' => 'AqilixAPI\Image\V1\Rest\Image\Controller::entity',
                // 'method' => 'PATCH',
            ),
            'delete' => array(
                // 'resource' => 'AqilixAPI\Image\V1\Rest\Image\Controller::entity',
                // 'method' => 'DELETE',
            )
        )
    ),
);
