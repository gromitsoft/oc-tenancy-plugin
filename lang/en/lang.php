<?php

return [
    'models'         => [
        'tenant' => [
            'validation' => [
                'name' => [
                    'required' => 'Enter tenant name',
                ],
            ],
            'columns'    => [
                'name'      => [
                    'label' => 'Name',
                ],
                'is_active' => [
                    'label' => 'Is active',
                ],
                'domains'   => [
                    'label' => 'Domains',
                ],
            ],
            'fields'     => [
                'name'      => [
                    'label' => 'Name',
                ],
                'is_active' => [
                    'label' => 'Is active',
                ],
                'domains'   => [
                    'label' => 'Domains',
                ],
            ],
        ],
        'domain' => [
            'validation' => [
                'tenant_id' => [
                    'required' => 'Choose tenant',
                    'exists'   => 'Selected tenant does not exist',
                ],
                'url'       => [
                    'required' => 'Enter domain',
                    'url'      => 'Domain is incorrect',
                    'unique'   => 'Domain already exists'
                ],
            ],
            'columns'    => [
                'url'       => [
                    'label' => 'Domain',
                ],
                'is_active' => [
                    'label' => 'Is active',
                ],
            ],
            'fields'     => [
                'url'       => [
                    'label' => 'Domain',
                ],
                'is_active' => [
                    'label' => 'Is active',
                ],
            ],
        ],
    ],
    'controllers'    => [
        'tenants' => [
            'config'   => [
                'list'     => [
                    'title' => 'Manage tenants',
                ],
                'form'     => [
                    'name'    => 'Tenant',
                    'create'  => [
                        'title'      => 'New tenant',
                        'flashSaved' => 'Tenant created',
                    ],
                    'update'  => [
                        'title'       => 'Edit tenant',
                        'flashSaved'  => 'Tenant saved',
                        'flashDelete' => 'Tenant deleted',
                    ],
                    'preview' => [
                        'title' => 'View tenant',
                    ],
                ],
                'relation' => [
                    'domains' => [
                        'label' => 'Domain',
                        'view'  => [
                            'toolbarButtons' => [
                                'create' => 'Add domain',
                                'delete' => 'Delete',
                            ],
                        ],
                    ],
                ],
            ],
            'partials' => [
                'list_toolbar' => [
                    'btn_create' => 'New tenant',
                    'btn_delete' => [
                        'label'   => 'Delete selected',
                        'confirm' => 'Are you sure you want to delete selected tenants?',
                    ],
                ],
                'breadcrumbs'  => [
                    'title'       => 'Tenants',
                    'fatal_error' => 'Error!',
                ],
                'tenant'       => [
                    'fields' => [
                        'domains_create' => [
                            'message' => 'You will can add domains after creating tenant',
                        ],
                    ],
                ],
            ],
            'pages'    => [
                'common' => [
                    'or'             => 'or',
                    'cancel'         => 'Cancel',
                    'return_to_list' => 'Return to tenants list',
                ],
                'create' => [
                    'btn_create'           => [
                        'label'          => 'Create',
                        'load_indicator' => 'Creating...',
                    ],
                    'btn_create_and_close' => [
                        'label'          => 'Create and close',
                        'load_indicator' => 'Creating...',
                    ],
                ],
                'update' => [
                    'btn_save'           => [
                        'label'          => 'Save',
                        'load_indicator' => 'Saving...',
                    ],
                    'btn_save_and_close' => [
                        'label'          => 'Save and close',
                        'load_indicator' => 'Saving...',
                    ],
                ],
            ],
        ],
    ],
    'components'     => [
        'current_tenant' => [
            'details'    => [
                'name'        => 'Tenant',
                'description' => 'Adds current tenant to page',
            ],
            'properties' => [
                'redirect' => [
                    'title'       => 'Redirect',
                    'description' => 'Redirect user if there is no current tenant',
                    'options'     => [
                        'no_redirect' => '- No redirect -'
                    ],
                ],
            ],
        ],
    ],
    'exceptions'     => [
        'tenanted_settings_need_base_settings'     => 'Model :modelClass need SettingsModel behavior.',
        'tenanted_settings_only_for_models'        => 'MakeSettingsModelTenantAware '
            . 'may be used only with models. :className is not a model.',
        'tenanted_model_only_for_models'           => 'MakeModelTenantAware and MakeModelUseTenantConnection '
            . 'may be used only with models. :className is not a model.',
        'tenant_connection_is_not_set'             => 'Database connection :connectionName is not configured. '
            . 'Add new connection in config/database.php.',
        'current_tenant_is_not_set'                => 'Current tenant is not set',
        'tenant_not_found_in_tenant_aware_command' => 'Tenant is not determined in tenant aware command ":command.".',
        'tenant_cant_delete_self'                  => 'Current teanant cannot be deleted',
        'tenant_not_found_in_tenant_aware_job'     => [
            'no_id_set'       => 'Current tenant is not determined in tenant aware job ":jobName".'
                . ' Job payload does not have `tenantId`.',
            'no_tenant_found' => 'Current tenant is not determined in tenant aware job ":jobName".'
                . ' Tenant can not be found by `tenantId` from payload.',
        ],
        'tenanted_plugin_updated_exception'        => [
            'plugin_not_found'       => 'Plugin ":pluginName" not found.',
            'plugin_is_not_tenanted' => 'Plugin ":pluginName" is not tenant aware.',
        ],
        'tenant_not_found'                         => 'Tenant not found',
    ],
    'permissions'    => [
        'tabs'                    => [
            'tenants' => 'Tenants',
        ],
        'manage_tenants'          => 'Manage tenants',
        'override_current_tenant' => 'Override the current tenant',
    ],
    'commands'       => [
        'plugin_update' => [
            'arguments' => [
                'plugin_code' => 'Plugin code. If plugin_code is not set then all plugins will be updated.',
            ],
            'options'   => [
                'tenant' => 'Tenant id. "all" for all tenants.',
            ],
        ],
    ],
    'report_widgets' => [
        'current_tenant' => [
            'label'                     => 'Current tenant',
            'current_tenant_is_not_set' => 'Current tenant is not set',
            'select_tenant'             => 'Select tenant',
            'reset_override'            => 'Reset',
            'choose_form'               => [
                'popup_title'         => 'Select tenant',
                'choose_btn'          => 'Choose',
                'cancel_btn'          => 'Cancel',
                'tenant_not_selected' => 'Tenant is not selected',
                'current_tenant_id'   => [
                    'label'  => 'Tenant',
                    'title'  => 'Choose tenant',
                    'prompt' => 'Click %s for choose tenant'
                ],
            ],
        ],
    ],
];
