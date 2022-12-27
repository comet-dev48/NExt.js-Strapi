import { Module } from '@nestjs/common';
import { MarketplaceEntity } from 'src/database/entities/marketplace.entity';
import { NestjsQueryGraphQLModule } from '@nestjs-query/query-graphql';
import { NestjsQueryTypeOrmModule } from '@nestjs-query/query-typeorm';
import { MarketplaceDto } from 'src/dto/marketplace.dto';

@Module({
  imports: [
    NestjsQueryGraphQLModule.forFeature({
      imports: [NestjsQueryTypeOrmModule.forFeature([MarketplaceEntity])],
      resolvers: [{ DTOClass: MarketplaceDto, EntityClass: MarketplaceEntity }],
    }),
  ],
})
export class MarketplaceModule {}
