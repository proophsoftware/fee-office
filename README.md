# The Fee Office - DDD, CQRS, ES explained

**Note: The demo and book are both work in progress. If you like it please share it with your friends. The more people know about it the more can learn from it.**

The Fee Office is a demo application based on the *es-emergency-call* [Need help with finding aggregates and bounded contexts in our domain](https://github.com/proophsoftware/es-emergency-call/issues/7) by [@enumag](https://github.com/enumag).

It is a prototype implementation showing the result of a model exploration and knowledge crunching process.

## Read The Book

The demo application ships with an [eBook](https://proophsoftware.github.io/fee-office/intro/about.html#1-1). It contains a summary of the emergency call, explanations about
the knowledge crunching process and design decisions.


## Editing the book

The book is [in the book tree](book/), and can be compiled using [bookdown](http://bookdown.io) and [Docker](https://www.docker.com/).

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
