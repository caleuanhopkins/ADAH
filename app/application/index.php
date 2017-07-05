<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerNamespaces(
    [
        "Adah" => __DIR__ . "/models/",
    ]
);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
$di->set(
    "db",
    function () {
        return new PdoMysql(
            [
                "host"     => "db",
                "username" => "admin",
                "password" => "admin",
                "dbname"   => "adah",
            ]
        );
    }
);

// Create and bind the DI to the application
$app = new Micro($di);

$app->get(
    "/api/agency/{name}",
    function ($name) use ($app) {
        $phql = "SELECT * FROM Adah\\Agency where name='$name'";

        $robots = $app->modelsManager->executeQuery($phql);

        $data = [];

        foreach ($robots as $robot) {
            $data[] = [
                "id"   => $robot->id,
                "name" => $robot->name,
            ];
        }

        echo json_encode($data);
    }
);

$app->handle();