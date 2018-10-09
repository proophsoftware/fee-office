# Installation

Docker is the only supported way to run the demo application. The commands shown here work on a Linux system
with [Git](https://git-scm.com/), [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) installed.

```bash
git clone https://github.com/proophsoftware/fee-office.git
cd fee-office
docker run --rm -it -v $(pwd):/app prooph/composer:7.2 install
sudo chown $(id -u -n):$(id -g -n) . -R
docker-compose up -d
```

## Troubleshooting

With the command `docker-compose ps` you can list the running containers.
Make sure that all required ports are available on your machine. If not you can modify port mapping in the `docker-compose.yml`.

### Have you tried turning it off and on again?

If something does not work as expected try to restart the containers first:

```bash
$ docker-compose down
$ docker-compose up -d
```

### Projection reset

TODO


*To better understand the system read about "autonomous modules" on the next page.*