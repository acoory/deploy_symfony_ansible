<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/auth' => [[['_route' => 'api_auth', '_controller' => 'App\\Controller\\ApiAuthController::index'], null, ['POST' => 0], null, false, false, null]],
        '/api' => [[['_route' => 'app_api', '_controller' => 'App\\Controller\\ApiController::index'], null, null, null, false, false, null]],
        '/api/users' => [
            [['_route' => 'app_api_users', '_controller' => 'App\\Controller\\ApiController::users'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_user_create', '_controller' => 'App\\Controller\\ApiController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'app_api_user', '_controller' => 'App\\Controller\\ApiController::update'], null, ['PUT' => 0], null, false, false, null],
        ],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\LoginController::index'], null, null, null, false, false, null]],
        '/api/orders' => [[['_route' => 'get_all_orders', '_controller' => 'App\\Controller\\OrderController::getAllOrders'], null, ['GET' => 0], null, true, false, null]],
        '/api/carts' => [
            [['_route' => 'app_panier', '_controller' => 'App\\Controller\\PanierController::getPanierByUserId'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_panier_create', '_controller' => 'App\\Controller\\PanierController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'app_panier_removeallproducts', '_controller' => 'App\\Controller\\PanierController::removeAllProducts'], null, ['DELETE' => 0], null, false, false, null],
        ],
        '/api/product' => [[['_route' => 'app_product', '_controller' => 'App\\Controller\\ProductController::index'], null, null, null, false, false, null]],
        '/api/products' => [
            [['_route' => 'app_api_products', '_controller' => 'App\\Controller\\ProductController::find_all'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_product_create', '_controller' => 'App\\Controller\\ProductController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/login_check' => [[['_route' => 'api_login_check'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|users/([^/]++)(?'
                        .'|(*:67)'
                    .')'
                    .'|orders/([^/]++)(*:90)'
                    .'|carts/(?'
                        .'|([^/]++)(?'
                            .'|(*:117)'
                        .')'
                        .'|validate(*:134)'
                    .')'
                    .'|products/([^/]++)(?'
                        .'|(*:163)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        67 => [
            [['_route' => 'app_api_user_delete', '_controller' => 'App\\Controller\\ApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'app_api_user_show', '_controller' => 'App\\Controller\\ApiController::show'], ['id'], ['GET' => 0], null, false, true, null],
        ],
        90 => [[['_route' => 'get_order_by_id', '_controller' => 'App\\Controller\\OrderController::getOrderById'], ['order_id'], ['GET' => 0], null, false, true, null]],
        117 => [
            [['_route' => 'app_panier_addproduct', '_controller' => 'App\\Controller\\PanierController::addProduct'], ['product_id'], ['POST' => 0], null, false, true, null],
            [['_route' => 'app_panier_removeproduct', '_controller' => 'App\\Controller\\PanierController::removeProduct'], ['product_id'], ['DELETE' => 0], null, false, true, null],
        ],
        134 => [[['_route' => 'app_panier_validatepanier', '_controller' => 'App\\Controller\\PanierController::validatePanier'], [], ['GET' => 0], null, false, false, null]],
        163 => [
            [['_route' => 'app_product_show', '_controller' => 'App\\Controller\\ProductController::find_by_id'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_product_update', '_controller' => 'App\\Controller\\ProductController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_product_delete', '_controller' => 'App\\Controller\\ProductController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
