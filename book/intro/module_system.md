# Autonomous Modules

The Fee Office is split into 5 bounded contexts. Each context is represented as a software module in
our system. But we don't use a monolithic MVC module approach like you know from ZendFramework 2 modules or Symfony bundles.
Instead we use the lightweight [module system](https://docs.zendframework.com/zend-expressive/v3/features/modular-applications/)
provided by Zend Expressive.

{.alert .alert-info}
The technique used in the demo is not bound to zend expressive. We've picked it because expressive uses
PSR standards and has a module system that works the way we need it. Anyway, you can achieve the same
project structure using any existing PHP framework and composer. We recommend asking the respective framework
community if you're not sure how to do it with your framework of choice.

## The Rules

{.alert .alert-warning}
Each module should be completely independent of any other module even if all modules are deployed on the same server.

To achieve module autonomy we use a special set up. It is a combination of Expressive modules which are also composer packages.
More on that in a minute. Furthermore, an application layer acts as the glue layer to coordinate communication between users/clients and modules
as well as between the modules "talking" to each other.

1. Each module has its own URL root path, f.e. the *RealtyRegistration* module uses the path `/realty/...`.
2. Each module has its own `composer.json` and only uses dependencies defined in that `composer.json` OR provided by the application layer.
3. Each module has at least a dedicated database schema assigned to it, if not its own database(s).
4. No module is allowed to use a class or function defined in another module.

The following diagram illustrates the architecture:

![Modules One Deployment](../img/modules_monolith.png)

If we later want to deploy one or more modules separated from the others (turn a module into a microservice), we can "easily" do that by deploying
the module together with the application layer and globally defined dependencies provided by the application layer.

![Modules Multiple Deployments](../img/modules_two_deployments.png)



