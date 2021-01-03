# Plugins in multi tenant setup

For working in multi tenant setups all plugins must be built with tenancy in mind.

Or, if you want to use an already existing plugin, you can adapt it.

## Multi database

In multi database setups all tenant aware plugins must be added to list in **gromit.tenancy::database.tenant_aware_plugins**.