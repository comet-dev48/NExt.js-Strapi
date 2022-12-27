import { Module } from '@nestjs/common';
import { NestjsQueryGraphQLModule } from '@nestjs-query/query-graphql';
import { NestjsQueryTypeOrmModule } from '@nestjs-query/query-typeorm';
import { AssetDto } from 'src/dto/asset.dto';
import { AssetEntity } from 'src/database/entities/asset.entity';
import { AssetResolver } from './asset.resolver';
import { HttpModule } from '@nestjs/axios';

@Module({
  providers: [AssetResolver],
  imports: [HttpModule]
  // imports: [
  //   NestjsQueryGraphQLModule.forFeature({
  //     imports: [NestjsQueryTypeOrmModule.forFeature([AssetEntity])],
  //     resolvers: [{ DTOClass: AssetDto, EntityClass: AssetEntity }],
  //   }),
  // ],
})
export class AssetModule {}
