# Console commands

GromIT.Tenancy provides these console commands:

## tenancy:plugin-update

This command updates plugins in tenants databases.

This command accept **plugin_code** optional argument and **tenant** required option.

If plugin_code is not specified, then all plugins will be updated.

Tenant can be tenant id or 'all' for all tenants.

## tenancy:plugin-refresh

This comment refreshes plugins in tenants databases.

Arguments and options same as in tenancy:plugin-update.