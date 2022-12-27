## Description

Rarespot API using [NestJS](https://github.com/nestjs/nest) with TypeORM and GraphQL

## Installation

```bash
yarn
```
#### Configuring the environment variables:
```bash
cp .env.example .env
```
Use your local MySQL parameters (usually the port is `3306` and host is `localhost`), and `PORT` to `8080` (make sure that the client and server are not running on the same port).

## Database synchronization with latest migrations
To sync your DB with latest migrations, run:

```bash
yarn db:sync
```
To create new migration after changing an entity, run:
```bash
yarn migration:generate <migration name>
```

To apply migration(s), run:
```bash
yarn migration:run
```

To revert latest migration, run:
```bash
yarn migration:revert
```

## Running the app

```bash
# development
yarn start

# watch mode
yarn start:dev

# production mode
yarn start:prod
```
## GraphQL Playground

The GraphQL playground is available at http://localhost:<PORT>/graphql

## Test

```bash
# unit tests
yarn test

# e2e tests
yarn test:e2e

# test coverage
yarn test:cov
```

## Author

[Arayik Hovhannisyan](https://github.com/arayik-99)
