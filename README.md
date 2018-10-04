# The Fee Office - DDD, CQRS, ES explained


## Editing the book

The book is [in the docs tree](docs/), and can be compiled using [bookdown](http://bookdown.io) and [Docker](https://www.docker.com/).

```bash
$ docker run --rm -it -v $(pwd):/app prooph/composer:7.2
$ docker run -it --rm -e CSS_BOOTSWATCH=lumen -e CSS_PRISM=ghcolors -v $(pwd):/app sandrokeil/bookdown:develop docs/bookdown.json
$ docker run -it --rm -p 8080:8080 -v $(pwd):/app php:7.2-cli php -S 0.0.0.0:8080 -t /app/docs/html
```

## Powered by prooph software

[![prooph software](https://github.com/codeliner/php-ddd-cargo-sample/blob/master/docs/assets/prooph-software-logo.png)](http://prooph.de)

Fee Office is a demo application maintained by the [prooph software team](http://prooph-software.de/). The source code of the Fee Office
is open sourced along with an [online book](https://proophsoftware.github.io/fee-office/) covering design decisions and explanations.

Prooph software offers commercial support and workshops for [Event Machine](https://github.com/proophsoftware/event-machine) as well as for the [prooph components](http://getprooph.org/).

If you are interested in this offer or need project support please [get in touch](http://getprooph.org/#get-in-touch).
