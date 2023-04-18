<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    'api_auth' => [[], ['_controller' => 'App\\Controller\\ApiAuthController::index'], [], [['text', '/api/auth']], [], [], []],
    'app_api' => [[], ['_controller' => 'App\\Controller\\ApiController::index'], [], [['text', '/api']], [], [], []],
    'app_api_users' => [[], ['_controller' => 'App\\Controller\\ApiController::users'], [], [['text', '/api/users']], [], [], []],
    'app_api_user_create' => [[], ['_controller' => 'App\\Controller\\ApiController::create'], [], [['text', '/api/users/create']], [], [], []],
    'app_api_user' => [['id'], ['_controller' => 'App\\Controller\\ApiController::update'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/users']], [], [], []],
    'app_api_user_delete' => [['id'], ['_controller' => 'App\\Controller\\ApiController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/users']], [], [], []],
    'app_api_user_show' => [['id'], ['_controller' => 'App\\Controller\\ApiController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/users']], [], [], []],
    'app_login' => [[], ['_controller' => 'App\\Controller\\LoginController::index'], [], [['text', '/login']], [], [], []],
    'app_panier' => [['user_id'], ['_controller' => 'App\\Controller\\PanierController::getPanierByUserId'], [], [['variable', '/', '[^/]++', 'user_id', true], ['text', '/api/panier']], [], [], []],
    'app_panier_create' => [[], ['_controller' => 'App\\Controller\\PanierController::create'], [], [['text', '/api/panier/create']], [], [], []],
    'app_panier_addproduct' => [['product_id'], ['_controller' => 'App\\Controller\\PanierController::addProduct'], [], [['variable', '/', '[^/]++', 'product_id', true], ['text', '/api/panier/add']], [], [], []],
    'app_panier_removeproduct' => [['product_id'], ['_controller' => 'App\\Controller\\PanierController::removeProduct'], [], [['variable', '/', '[^/]++', 'product_id', true], ['text', '/api/panier/remove']], [], [], []],
    'app_panier_removeallproducts' => [[], ['_controller' => 'App\\Controller\\PanierController::removeAllProducts'], [], [['text', '/api/panier/removeAll']], [], [], []],
    'app_panier_validatepanier' => [[], ['_controller' => 'App\\Controller\\PanierController::validatePanier'], [], [['text', '/api/panier/validate']], [], [], []],
    'app_product' => [[], ['_controller' => 'App\\Controller\\ProductController::index'], [], [['text', '/api/product']], [], [], []],
    'app_product_show' => [['id'], ['_controller' => 'App\\Controller\\ProductController::find_by_id'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/product']], [], [], []],
    'app_api_products' => [[], ['_controller' => 'App\\Controller\\ProductController::find_all'], [], [['text', '/api/products']], [], [], []],
    'app_api_product_create' => [[], ['_controller' => 'App\\Controller\\ProductController::create'], [], [['text', '/api/products/create']], [], [], []],
    'app_api_product_update' => [['id'], ['_controller' => 'App\\Controller\\ProductController::update'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/products/update']], [], [], []],
    'app_api_product_delete' => [['id'], ['_controller' => 'App\\Controller\\ProductController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/api/products/delete']], [], [], []],
    'api_login_check' => [[], [], [], [['text', '/api/login_check']], [], [], []],
];
