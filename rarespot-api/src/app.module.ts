import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { GraphQLModule } from '@nestjs/graphql';
import { ScheduleModule } from '@nestjs/schedule';
import { DatabaseModule } from 'src/database/database.module';
import { CollectionModule } from 'src/collection/collection.module';
import { JobsModule } from 'src/jobs/jobs.module';
import { MarketplaceModule } from 'src/marketplace/marketplace.module';
import { AssetModule } from 'src/assets/asset.module';

@Module({
  imports: [
    ConfigModule.forRoot(),
    ScheduleModule.forRoot(),
    GraphQLModule.forRootAsync({
      useFactory: () => ({
        playground: true,        
        introspection: true,
        autoSchemaFile: 'schema.gql',
      }),
    }),
    DatabaseModule,
    JobsModule,
    CollectionModule,
    AssetModule,
    MarketplaceModule,
  ],
})
export class AppModule {}
