<?php
namespace <%= packageName %>;


use <%= packageName %>\Controller\Factory\<%= className %>ControllerFactory;
use <%= packageName %>\Service\Factory\<%= className %>ServiceFactory;
use <%= packageName %>\Controller\<%= className %>Controller;
use <%= packageName %>\Service\<%= className %>Service;


return [
    'router' => [
        'routes' => [
            //<%=(_.replace(_.snakeCase(className),"_","-")).toLowerCase()%> routers
            '<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>Update' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/update[/:id]',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => <%= className %>Controller::class,
                    ],
                ],
            ],
            '<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>Get' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>[/:id]',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => <%= className %>Controller::class,
                    ],
                ],
            ],
            '<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>Delete' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/delete[/:id]',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => <%= className %>Controller::class,
                    ],
                ],
            ],
            '<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>Create' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>/create',
                    'defaults' => [
                        'controller' => <%= className %>Controller::class,
                    ],
                ],
            ],
            '<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>List' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/<%=_.replace(_.snakeCase(className),"_","-").toLowerCase()%>',
                    'defaults' => [
                        'controller' => <%= className %>Controller::class,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            <%= className %>Service::class => <%= className %>ServiceFactory::class
        ],
    ],
    'controllers' => [
        'factories' => [
            <%= className %>Controller::class => <%= className %>ControllerFactory::class
        ],
    ],
    'view_manager' => [
		'strategies' => [
			'ViewJsonStrategy',
		],
	],
    'strategies' => [
        'ViewJsonStrategy',
    ],
];
