# Tasks

GromIT.Tenancy has three types of tasks:

- Tasks executed after tenant is created. Listed in **gromit.tenancy::tenancy.create_tenant_tasks**
- Tasks executed after tenant is deleted. Listed in **gromit.tenancy::tenancy.delete_tenant_tasks**
- Tasks executed when tenant is switched. Listed in **gromit.tenancy::tenancy.switch_tenant_tasks**

## Tasks provided by GromIT.Tenancy

### Create tenant tasks

There are three tasks, executed by default, after a tenant has been created:

#### \GromIT\Tenancy\Tasks\CreateTenant\CreateDatabase

Create a database for created tenant, and save database name to tenant.

By default, for creating databases we are using **\GromIT\Tenancy\DatabaseCreators\MysqlDatabaseCreator**. You can
change it in **gromit.tenancy::database.database_creator**.

Default database creator works only with mysql.

For creating or deleting databases we use username and password placed in
**gromit.tenancy::database.create_database_auth**. If there are nulls then we use username and password from tenant
connection.

#### \GromIT\Tenancy\Tasks\CreateTenant\Migrate

This task runs migrations of all base tables, listed in **gromit.tenancy::database.tenant_db_default_migrations**.

After that, it runs migrations of all tenant aware plugins listed in **gromit.tenancy::database.tenant_aware_plugins**.

#### \GromIT\Tenancy\Tasks\CreateTenant\CreateStorageRoot

This task creates root for tenant storage disk. By default, this is /storage/tenant/{tenant_id} directory.
#
## Delete tenant tasks

These tasks executed after tenant has been deleted:

#### \GromIT\Tenancy\Tasks\DeleteTenant\DeleteDatabase

This task is deleting tenant's database.

**gromit.tenancy::database.database_creator** is using for deleted databases.

#### \GromIT\Tenancy\Tasks\DeleteTenant\DeleteStorageRoot

This task is deleting root directory of tenant storage disk.
#
## Switch tenant tasks

These tasks executing on switching current tenant in order listed in the config. On making tenant current executes
**makeCurrent** method, on forgetting current tenant executes **forgetCurrent** method of task.

#### \GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantDatabaseConnection

This task changing tenant database connection.

By default, tenant connection name is **tenant**. You can change it in **gromit.tenancy::
database.tenant_connection_name**.

Don't forget to change connection name in /config/database.php.

#### \GromIT\Tenancy\Tasks\SwitchTenant\ChangeCachePrefix

This task changing cache prefix for reflect tenant id.

#### \GromIT\Tenancy\Tasks\SwitchTenant\ChangeStorageDisk

This task changing tenant storage disk root and url.

#### \GromIT\Tenancy\Tasks\SwitchTenant\ChangeResourcesStorage

This task changing folder and path options of

- cms.storage.uploads
- cms.storage.media
- cms.storage.resized

#### \GromIT\Tenancy\Tasks\SwitchTenant\ChangeTenantLoggingChannel

This task tenant logging channel path.

## Creating your own tasks

If you want to make your own tasks, you can do this.

Create tenant tasks must implement **\GromIT\Tenancy\Tasks\CreateTenantTask** interface.

Delete tenant tasks must implement **\GromIT\Tenancy\Tasks\DeleteTenantTask** interface.

Switch tenant tasks must implement **\GromIT\Tenancy\Tasks\SwitchTenantTask** interface.

Don't forget to add your tasks in tenancy config file.