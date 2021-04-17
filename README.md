# Desafio Back-end PHP

Repositório referente a um desafio backend php

Para usar o ambiente docker
```
docker-compose up
```

Prepare o arquivo com variáveis de ambiente
```
cp .env.example .env
```

Gere uma nova chave para a aplicação laravel
```
php artisan key:generate
```

Rode as migrations para criar o banco de dados
```
php artisan migrate
```

Rode a seeder para criar alguns usuários na base
```
php artisan db:seed
```

Para executar os testes de controllers
```
php artisan artisan test
```

## Para testar a aplicação

Se usar o ambiente docker para rodar a aplicação, a base url será:
```
desafio-backend-php.localhost:8080
```


### Exemplo:

POST /transaction

```json
{
    "value" : 100.00,
    "payer" : 4,
    "payee" : 10
}
```
