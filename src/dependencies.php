<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['services.cities'] = function ($c) {
    return new \Weather\Services\CitiesService();
};

$container['repositories.weather.yahoo'] = function ($c) {
    return new \Weather\Repositories\WeatherRepository\YahooWeatherRepository();
};

$container['controllers.index'] = function ($c) {
    return new \Weather\Controllers\IndexController();
};

$container['controllers.cities'] = function ($c) {
    return new \Weather\Controllers\CitiesController(
        $c['services.cities'],
        $c['repositories.weather.yahoo']
    );
};
