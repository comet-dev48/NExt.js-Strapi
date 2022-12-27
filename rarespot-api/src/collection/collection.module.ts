import { Module } from '@nestjs/common';
import { NestjsQueryGraphQLModule } from '@nestjs-query/query-graphql';
import { NestjsQueryTypeOrmModule } from '@nestjs-query/query-typeorm';
import { CollectionDto } from 'src/dto/collection.dto';
import { CollectionEntity } from 'src/database/entities/collection.entity';

@Module({
  imports: [
    NestjsQueryGraphQLModule.forFeature({
      imports: [NestjsQueryTypeOrmModule.forFeature([CollectionEntity])],
      resolvers: [{ DTOClass: CollectionDto, EntityClass: CollectionEntity, enableTotalCount: true }],
    }),
  ],
})
export class CollectionModule {}
