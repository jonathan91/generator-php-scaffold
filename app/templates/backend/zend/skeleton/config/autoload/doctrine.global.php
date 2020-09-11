<?php
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' => '<%=props.db%>://<%=props.dbUser%>:<%=props.dbPassword%>@db:<%=props.dbPort%>/<%=props.dbName%>'
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    #new-entity-mapper
                ],
            ],
            'annotation_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    getcwd() . "/src/App/Entity"
                ],
            ],
        ],
    ],
];