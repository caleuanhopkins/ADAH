<?php

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
                "host"     => "localhost",
                "username" => "admin",
                "password" => "admin",
                "dbname"   => "adah",
            ]
        );
    }
);

// Create and bind the DI to the application
$app = new Micro($di);

use \Phalcon\Db\Column as Column;

$connection->createTable(
    'agency',
    null,
    [
       'columns' => [
            new Column(
                'id',
                [
                    'type'          => Column::TYPE_INTEGER,
                    'size'          => 10,
                    'notNull'       => true,
                    'autoIncrement' => true,
                    'primary'       => true,
                ]
            ),
            new Column(
                'name',
                [
                    'type'    => Column::TYPE_VARCHAR,
                    'size'    => 70,
                    'notNull' => true,
                ]
            ),
            new Column(
                'year',
                [
                    'type'    => Column::TYPE_DATETIME,
                ]
            ),
        ]
    ]
);

$app->get(
    "/api/agency",
    function () use ($app) {
        $phql = "SELECT * FROM Adah\\Agency ORDER BY name";

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