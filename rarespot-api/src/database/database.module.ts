import { Module } from '@nestjs/common';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import { ENTITIES_DIR, MIGRATIONS_DIR } from 'src/constants';
import { Environment } from 'src/enums';
import { buildPath } from 'src/utils';

@Module({
  imports: [
    TypeOrmModule.forRootAsync({
      imports: [ConfigModule],
      inject: [ConfigService],
      useFactory: async (configService: ConfigService) => {
        return {
          type: 'postgres',
          database: configService.get(Environment.DB_NAME),
          host: configService.get(Environment.DB_HOST),
          port: configService.get<number>(Environment.DB_PORT),
          username: configService.get(Environment.DB_USERNAME),
          password: configService.get(Environment.DB_PASSWORD),
          ssl:
            configService.get(Environment.NODE_ENV) === 'production'
              ? {
                  rejectUnauthorized: false,
                }
              : false,
          entities: [buildPath(__dirname, `../${ENTITIES_DIR}`)],
          migrations: [buildPath(__dirname, `../${MIGRATIONS_DIR}`)],
        };
      },
    }),
  ],
})
export class DatabaseModule {}
