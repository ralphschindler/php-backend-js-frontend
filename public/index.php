<?php

use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app->register(new ServiceControllerServiceProvider);
$app['debug'] = true;

$app['db'] = function () {
    $db = new PDO('sqlite:../db/remindersapp.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $db;
};

$app['reminderapp_model_reminder_repository'] = function ($services) {
    return new ReminderApp\Model\ReminderRepository($services['db']);
};

$app['reminderapp_model_location_repository'] = function ($services) {
    return new ReminderApp\Model\LocationRepository($services['db']);
};

$app['reminderapp_endpoint_index'] = function () {
    return new ReminderApp\Endpoint\IndexEndpoint();
};

$app['reminderapp_endpoint_reminder'] = function ($services) use ($app) {
    return new ReminderApp\Endpoint\ReminderEndpoint($services['request'], $services['reminderapp_model_reminder_repository']);
};

$app['reminderapp_endpoint_location'] = function ($services) {
    return new ReminderApp\Endpoint\LocationEndpoint($services['reminderapp_model_location_repository']);
};

$app->before(function (Request $request) {
    // to allow CORS (browsers, JS applications - there is also a provider for this in Silex)
    if ($request->getMethod() === 'OPTIONS') {
        $optionHeaders = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With'
        ];
        return new Response(null, 200, $optionHeaders);
    }

    $accept = $request->headers->get('accept');

    // you asked for HTML, I'll deliver you the dashboard (JS App)
    if (strpos($accept, 'text/html') !== false && $request->getPathInfo() === '/') {
        return new Response(null, 302, ['Location' => '/dashboard/index.html']);
    }

    // we're only speaking json
    if (!strpos($accept, 'json') !== false) {
        http_response_code(406);
        exit;
    }

    // parse json into attributes
    $contentType = $request->headers->get('Content-Type');
    if (strpos($contentType, '/json') !== false || strpos($contentType, '+json') !== false) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }

    // this is a great place to add 1) better accept handling, 2) better content negotiation
});

$app->on('kernel.view', function (GetResponseForControllerResultEvent $e) {
    $result = $e->getControllerResult();
    $response = new Response;
    if ($result instanceof ReminderApp\Resource\ResourceInterface) {
        $headers = ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Methods' => 'GET, OPTIONS', 'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With'];
        $response->headers->add(array_merge($headers, $result->getHeaders()));
        $response->setStatusCode($result->getResponseCode());
        $response->setContent($result->getContent());
        $e->setResponse($response);
    }
});

$app->match('/', 'reminderapp_endpoint_index:execute');
$app->match('/reminder/{id}', 'reminderapp_endpoint_reminder:execute')->value('id', null);
$app->match('/location/{id}', 'reminderapp_endpoint_location:execute')->value('id', null);
$app->run();