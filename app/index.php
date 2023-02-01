<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => getenv('REDIS_HOST'),
    'port'   => getenv('REDIS_PORT'),
]);

$router = new Phroute\Phroute\RouteCollector();

$router->get('/generate', function () use ($client): array {
    $id = \Ramsey\Uuid\Uuid::uuid4();
    $number = mt_rand((int) getenv('MIN_NUMBER'), (int) getenv('MAX_NUMBER'));
    $client->set($id->toString(), $number);

    return [
        'code' => 201,
        'content' => [
            'id' => $id,
            'number' => $number,
        ],
    ];
});

$router->get('/retrieve/{id:c}', function (string $id) use ($client): array {
    $number = $client->get($id);

    if ($number === null) {
        return [
            'code' => 404,
            'content' => [
                'error' => 'Number with this id doesn\'t exist',
            ],
        ];
    }

    return [
        'code' => 200,
        'content' => [
            'id' => $id,
            'number' => (int) $number,
        ],
    ];
});

$router->any('/', function (): array {
    return [
        'code' => 404,
        'content' => [
            'error' => 'Method not found',
        ],
    ];
});

try {
    $dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
    $response = $dispatcher->dispatch(
        $_SERVER['REQUEST_METHOD'],
        parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH)
    );
} catch (\Exception $exception) {
    $response = [
        'code' => 500,
        'content' => [
            'error' => getenv('ENV') === 'local' ? $exception->getMessage() : 'System error',
        ],
    ];
}

header('Content-Type: application/json');
http_response_code($response['code']);
echo json_encode($response['content']);
