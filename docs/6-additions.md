# currentTenant component

GromIT.Tenancy provides **currentTenant** component.

This component does two things:

- add $currentTenant variable to page
- redirect to another page if there is no current tenant

# CurrentTenant report widget

This report widget show the current tenant in the backend dashboard.

If current backend user has gromit.tenancy.override_current_tenant permission (or is superuser) this widget also
provides functionality for the override of current tenant. It can be useful if you want to see content of another tenant
without changing the domain.