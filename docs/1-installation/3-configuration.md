# Config files

GromIT.Tenancy has 4 config files:

## tenancy.php

This is the main tenancy config file. It contains:
- application container key 
- current tenant finder
- create, delete, and switch tenants tasks

## database.php

This config file used only with multi database setups. It contains options related to creating and using tenants databases.

Also, it contains list of plugins that must be migrated in tenants databases.

## storage.php

storage.php contains options for configuring **tenant** storage disk. Also, there are options for resources (media finder, file attachments and resized images) storage.

By default, Storage::disk('tenant') will work with /storage/tenant/<tenant_id> directory and resources will work with tenant disk.

## logging.php 

This file has option with name of tenant related logging channel. This config used by **\GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantLoggingChannel** task.