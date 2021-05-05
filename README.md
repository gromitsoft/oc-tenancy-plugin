# Introduction

GromIT.Tenancy is a plugin for OctoberCMS. It provides multitenancy functionality for OctoberCMS. 

It has features such as
- single database and multi database setup
- prefixing cache for tenants
- isolated storage for every tenant
- isolated log files for every tenant 

## Quick start

Quick start is designed for work with mysql in multi-database way.

1. Install the plugin as usual
2. Create **tenant** database connection. Just make a copy of your mysql connection and set database option of new connection to null
3. Add **tenant** disk to filesystem config. Use can use {tenant_id} as tenant identifier in root and url options
4. Add **tenant** channel to logging config. Use can use {tenant_id} as tenant identifier in path option

For creating tenants databases username and password of tenant connection will be used.

## Documentation

Full documentation can be found in **docs** directory.

## Requirements

GromIT.Tenancy requires OctoberCMS v2.

## Credits

Created by [Sergey Kasyanov](https://github.com/SergeyKasyanov) at [GromIT](https://grom-it.ru).