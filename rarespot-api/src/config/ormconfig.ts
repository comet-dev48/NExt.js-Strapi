import { ConnectionOptions } from 'typeorm';
import { ENTITIES_DIR, MIGRATIONS_DIR } from 'src/constants';
import { Environment } from 'src/enums';

const ENV = process.env[Environment.NODE_ENV];

const connectionOptions: ConnectionOptions = {
  type: 'postgres',
  database: process.env[Environment.DB_NAME],
  host: process.env[Environment.DB_HOST],
  port: +process.env[Environment.DB_PORT],
  username: process.env[Environment.DB_USERNAME],
  password: process.env[Environment.DB_PASSWORD],
  ssl:
    ENV === 'production'
      ? {
          rejectUnauthorized: false,
        }
      : false,
  entities: [`src/${ENTITIES_DIR}`],
  migrations: [`src/${MIGRATIONS_DIR}`],
  cli: {
    migrationsDir: 'src/database/migrations',
  },
};

export = connectionOptions;
