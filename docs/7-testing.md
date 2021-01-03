# Testing

GromIT.Tenancy has separated plugin **GromIT.TenancyTests** with unit tests.

This plugin also provides some migrations and models for testing purposes.

## Running tests

After installing GromIT.TenancyTests plugin you need to do some steps:

- make new mysql database for testing purposes
- create /config/testing directory
- copy here your /config/database.php file
- change database to newly created database
- make tenant connection in this new config

Go to /plugins/gromit/tenancytests directory and run

```shell
../../../vendor/bin/phpunit
```
