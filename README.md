# DOU Processor (Laravel 12)

Este projeto é uma aplicação Laravel que permite o upload de arquivos `.zip` contendo XMLs de publicações do Diário Oficial. Após o upload, os arquivos são descompactados e os dados XML são processados e salvos no banco de dados. Cada artigo processado é enviado para uma fila RabbitMQ para posterior consumo.

## Requisitos

- Docker + Docker Compose
- PHP 8.3 (caso queira rodar localmente sem Docker)
- Composer
- Make (opcional, para uso com scripts)

## Configuração

Clone este repositório e acesse o diretório:

```bash
git clone https://github.com/ahrocha/dou-processor.git
cd dou-processor
```

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Suba os containers com:

```bash
docker compose up --build -d
```

As migrations são executadas automaticamente:

## Executando a aplicação

A aplicação estará disponível em: [http://localhost:8080](http://localhost:8080)

Para enviar um arquivo `.zip`, use o seguinte comando `curl`:

```bash
curl -X POST http://localhost:8080/api/uploads \
  -F "arquivo=@S02052025.zip" \
  -H "Accept: application/json"
```

### exemplos de rota GET
```
GET http://localhost:8000/api/uploads

GET http://localhost:8000/api/uploads/:id

GET http://localhost:8000/api/artigos/:id

```

## Rabbit MQ
O RabbitMQ pode ser acessado em modo de gerenciamento pela url:
```
http://localhost:15672/
```
Caso não altere, os logins e senhas configurados são:
login: guest
senha: guest

## Workers

A aplicação possui duas filas:

1. **A fila de upload:** processa o `.zip`, extrai e salva os artigos no banco.
2. **A fila de publicação:** escuta eventos de `ArtigoCreated` e publica os dados na fila `artigos-criados`.

## Testes

Para rodar os testes automatizados:

```bash
docker exec -it dou-app php artisan test --env=testing
```

Certifique-se de que `.env.testing` está configurado corretamente para usar SQLite ou outro banco isolado.

## Fila (RabbitMQ)

A configuração da conexão RabbitMQ deve estar no `.env` com variáveis como:

---



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
