# The Fee Office - DDD, CQRS, ES explained

The Fee Office is a demo application based on the *es-emergency-call* [Need help with finding aggregates and bounded contexts in our domain](https://github.com/proophsoftware/es-emergency-call/issues/7) by [@enumag](https://github.com/enumag).

It is a prototype implementation showing the result of a model exploration and knowledge crunching process.

## What's insight?

An online book summarizes and documents the process of crunching knowledge and how we used the knowledge to identify bounded contexts and aggregates as well as
a good architecture for each bounded context and the system as a large.

*Note: The book and the prototype are both work in progress.*

## Architecture

The system is split into 5 contexts and each context is implemented as an autonomous module within a monolithic application. You can view those modules
as (mico)services deployed together. That said, we combine autonomy of bounded contexts (implemented as modules) with the ease of deploying and operating a monolithic system.
Because we keep modules separated (even on database level) we are able to split the system later and scale up individual modules if needed.
We also keep the model of each context decoupled from the other models - a very important property for a system that is constantly improved and reshaped.

## Technology



## Editing the book

The book is [in the docs tree](docs/), and can be compiled using [bookdown](http://bookdown.io) and [Docker](https://www.docker.com/).

```bash
$ docker run --rm -it -v $(pwd):/app prooph/composer:7.2
$ docker run -it --rm -e CSS_BOOTSWATCH=lumen -e CSS_PRISM=ghcolors -v $(pwd):/app sandrokeil/bookdown:develop book/bookdown.json
$ docker run -it --rm -p 8080:8080 -v $(pwd):/app php:7.2-cli php -S 0.0.0.0:8080 -t /app/docs
```

## Powered by prooph software

[![prooph software](https://github.com/codeliner/php-ddd-cargo-sample/blob/master/docs/assets/prooph-software-logo.png)](http://prooph.de)

Fee Office is a demo application maintained by the [prooph software team](http://prooph-software.de/). The source code of the Fee Office
is open sourced along with an [online book](https://proophsoftware.github.io/fee-office/) covering design decisions and explanations.

Prooph software offers commercial support and workshops for [Event Machine](https://github.com/proophsoftware/event-machine) as well as for the [prooph components](http://getprooph.org/).

If you are interested in this offer or need project support please [get in touch](http://getprooph.org/#get-in-touch).
