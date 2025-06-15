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
./test-local.sh
```
Relatórios serão gerados em /coverage

---

## Análise de código

### PHP Stan
```bash
composer analyse
```

### PHP CS
```bash
composer cs:check
composer cs:fix
```
