<?php 

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\DBAL\Driver\<%=props.dbType%>\Driver;

return [
    'doctrine' => [
        'connection' => [
            // default connection name
            'orm_default' => [
                'driverClass' => Driver::class,
                'params' => [
                    'host'     => 'db',
                    'port'     => '<%=props.dbPort%>',
                    'user'     => '<%=props.dbUser%>',
                    'password' => '<%=props.dbPassword%>',
                    'dbname'   => '<%=props.dbName%>',
                    'charset'   => 'utf8',
                    <% if (props.dbType === 'PDOMySql') { %>
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                    ]
                    <% } %>
                ],
            ],
        ],
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'annotation_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    # entity-name-mapper don't delete this line
                ],
            ],
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    # namespace-name-mapper don't delete this line
                ]
            ]
        ]
    ]
];
