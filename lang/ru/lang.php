<?php

return [
    'models'      => [
        'tenant' => [
            'validation' => [
                'name' => [
                    'required' => 'Введите наименование арендатора',
                ],
            ],
            'columns'    => [
                'name'      => [
                    'label' => 'Наименование',
                ],
                'is_active' => [
                    'label' => 'Активен',
                ],
                'domains'   => [
                    'label' => 'Домены',
                ],
            ],
            'fields'     => [
                'name'      => [
                    'label' => 'Наименование',
                ],
                'is_active' => [
                    'label' => 'Активен',
                ],
                'domains'   => [
                    'label' => 'Домены',
                ],
            ],
        ],
        'domain' => [
            'validation' => [
                'tenant_id' => [
                    'required' => 'Укажите арендатора',
                    'exists'   => 'Указанный арендатор не существует',
                ],
                'url'       => [
                    'required' => 'Введите домен',
                    'url'      => 'Домен должен быть корректной ссылкой',
                    'unique'   => 'Такой домен уже занят'
                ],
            ],
            'columns'    => [
                'url'       => [
                    'label' => 'Домен',
                ],
                'is_active' => [
                    'label' => 'Активен',
                ],
            ],
            'fields'     => [
                'url'       => [
                    'label' => 'Домен',
                ],
                'is_active' => [
                    'label' => 'Активен',
                ],
            ],
        ],
    ],
    'controllers' => [
        'tenants' => [
            'config'   => [
                'list'     => [
                    'title' => 'Управление арендаторами',
                ],
                'form'     => [
                    'name'    => 'Арендатор',
                    'create'  => [
                        'title'      => 'Новый арендатор',
                        'flashSaved' => 'Арендатор создан',
                    ],
                    'update'  => [
                        'title'       => 'Изменение арендатора',
                        'flashSaved'  => 'Арендатор сохранен',
                        'flashDelete' => 'Арендатор удален',
                    ],
                    'preview' => [
                        'title' => 'Просмотр арендатора',
                    ],
                ],
                'relation' => [
                    'domains' => [
                        'label' => 'Домен',
                        'view'  => [
                            'toolbarButtons' => [
                                'create' => 'Добавить домен',
                                'delete' => 'Удалить',
                            ],
                        ],
                    ],
                ],
            ],
            'partials' => [
                'list_toolbar' => [
                    'btn_create' => 'Новый арендатор',
                    'btn_delete' => [
                        'label'   => 'Удалить выбранных',
                        'confirm' => 'Вы действительно хотите удалить выбранных арендаторов?',
                    ],
                ],
                'breadcrumbs'  => [
                    'title'       => 'Арендаторы',
                    'fatal_error' => 'Ошибка!',
                ],
                'tenant'       => [
                    'fields' => [
                        'domains_create' => [
                            'message' => 'Вы сможете добавить домены после создания арендатора',
                        ],
                    ],
                ],
            ],
            'pages'    => [
                'common' => [
                    'or'             => 'или',
                    'cancel'         => 'Отменить',
                    'return_to_list' => 'Вернуться к списку арендаторов',
                ],
                'create' => [
                    'btn_create'           => [
                        'label'          => 'Создать',
                        'load_indicator' => 'Создание...',
                    ],
                    'btn_create_and_close' => [
                        'label'          => 'Создать и закрыть',
                        'load_indicator' => 'Создание...',
                    ],
                ],
                'update' => [
                    'btn_save'           => [
                        'label'          => 'Сохранить',
                        'load_indicator' => 'Сохранение...',
                    ],
                    'btn_save_and_close' => [
                        'label'          => 'Сохранить и закрыть',
                        'load_indicator' => 'Сохранение...',
                    ],
                ],
            ],
        ],
    ],
    'exceptions'  => [
        'tenant_connection_is_not_set' => 'Подключение к БД :connectionName не настроено. '
            . 'Создайте подключение в файле config/database.php.'
    ],
    'permissions' => [
        'tabs' => [
            'tenants' => 'Арендаторы',
        ],
        'manage_tenants' => 'Управление арендаторами'
    ],
];
