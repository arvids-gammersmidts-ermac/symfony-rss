User rss feed Project
===

##### User rss feed app based on Symfony 4.1+ & PHP 7.2+

### Installation with docker
```sh
sh ./setup.sh
```

#### Run the project
- Open this address on your browser:
    - [http://localhost:8010/](http://localhost:8011/)
- Database:
    - [http://localhost:8001/](http://localhost:8001/)
    - username: **docker**
    - password: **docker**

#### Run tests
```sh
cd app
vendor/bin/codecept run tests/
```

#### Stop the project
```sh
docker-compose stop
```
