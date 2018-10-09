# About The Fee Office

The Fee Office is a demo application based on the *es-emergency-call* [Need help with finding aggregates and bounded contexts in our domain](https://github.com/proophsoftware/es-emergency-call/issues/7) by [@enumag](https://github.com/enumag).

It is a prototype implementation showing the result of a model exploration and knowledge crunching process.

## What's insight?

An online book summarizes and documents the process of crunching knowledge and how we used the knowledge to identify bounded contexts and aggregates as well as
a good architecture for each bounded context and the system as a large.

*Note: The book and the prototype are both work in progress.*

## Architecture

The system is split into 5 contexts and each context is implemented as an **autonomous module** within a monolithic application. Think of those modules
as (mico)services deployed together. That said, we combine autonomy of bounded contexts (implemented as modules) with the ease of deploying and operating a monolithic system.
Because we keep modules separated (even on database level) we are able to split the system later and scale up individual modules if needed.
We also keep the **model** of each context decoupled from the other models - a very important property of a system that is constantly improved and reshaped.

## Technology

### Infrastructure

- [Docker](https://www.docker.com/) & [Docker Compose](https://docs.docker.com/compose/)
- [Nginx](https://www.nginx.com/)
- [PostgreSQL](https://www.postgresql.org/)

### Programming Language

- [PHP 7.x](http://php.net/)

### API Gateway & Module System

- [zend/expressive](https://docs.zendframework.com/zend-expressive/)

### Dependency Mgmt

- [composer](https://getcomposer.org/)

### Event Sourcing

- [Event Machine](https://proophsoftware.github.io/event-machine/)

### State Persistence

- [PostgreSQL Document Store](https://github.com/proophsoftware/postgres-document-store)

*Continue with setting up the demo application using Docker. You'll find instructions on the next page.*

