<?php

return [
    'models'         => [
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
    'controllers'    => [
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
    'components'     => [
        'current_tenant' => [
            'details'    => [
                'name'        => 'Арендатор',
                'description' => 'Добавляет текущего арендатора на страницу',
            ],
            'properties' => [
                'redirect' => [
                    'title'       => 'Перенаправление',
                    'description' => 'Перенаправить пользователя, если не установлен текущий арендатор',
                    'options'     => [
                        'no_redirect' => '- Без перенаправления -'
                    ],
                ],
            ],
        ],
    ],
    'exceptions'     => [
        'tenanted_settings_need_base_settings'     => 'Модель :modelClass требует наличия поведения SettingsModel.',
        'tenanted_settings_only_for_models'        => 'MakeSettingsModelTenantAware '
            . 'может быть использован только с моделями. :className не является моделью.',
        'tenanted_model_only_for_models'           => 'MakeModelTenantAware и MakeModelUseTenantConnection '
            . 'могут быть использованы только с моделями. :className не является моделью.',
        'tenant_connection_is_not_set'             => 'Подключение к БД :connectionName не настроено. '
            . 'Создайте подключение в файле config/database.php.',
        'current_tenant_is_not_set'                => 'Текущий арендатор не определен.',
        'tenant_not_found_in_tenant_aware_command' => 'При выполнении команды ":command." арендатор не был определен.',
        'tenant_cant_delete_self'                  => 'Нельзя удалить текущего арендатора',
        'tenant_not_found_in_tenant_aware_job'     => [
            'no_id_set'       => 'В задаче ":jobName" не определен текущий арендатор.'
                . ' В параметрах задачи отсутствует ключ `tenantId`.',
            'no_tenant_found' => 'В задаче ":jobName" не определен текущий арендатор.'
                . ' Арендатор не найден по ключу `tenantId` из параметров.',
        ],
        'tenanted_plugin_updated_exception'        => [
            'plugin_not_found'       => 'Плагин ":pluginName" не найден.',
            'plugin_is_not_tenanted' => 'Плагин ":pluginName" не предназначен для арендаторов.',
        ],
        'tenant_not_found'                         => 'Арендатор не найден',
    ],
    'permissions'    => [
        'tabs'                    => [
            'tenants' => 'Арендаторы',
        ],
        'manage_tenants'          => 'Управление арендаторами',
        'override_current_tenant' => 'Переопределение текущего арендатора',
    ],
    'commands'       => [
        'plugin_update' => [
            'arguments' => [
                'plugin_code' => 'Код плагина. Если не передать - будут обновлены все плагины.',
            ],
            'options'   => [
                'tenant' => 'ID арендатора. "all" для всех арендаторов.',
            ],
        ],
    ],
    'report_widgets' => [
        'current_tenant' => [
            'label'                     => 'Текущий арендатор',
            'current_tenant_is_not_set' => 'Текущий арендатор не определен',
            'select_tenant'             => 'Выбрать арендатора',
            'reset_override'            => 'Сбросить',
            'choose_form'               => [
                'popup_title'         => 'Выберите арендатора',
                'choose_btn'          => 'Выбрать',
                'cancel_btn'          => 'Отменить',
                'tenant_not_selected' => 'Арендатор не выбран',
                'current_tenant_id'   => [
                    'label'  => 'Арендатор',
                    'title'  => 'Выберите арендатора',
                    'prompt' => 'Кликните %s чтобы выбрать арендатора'
                ],
            ],
        ],
    ],
];
