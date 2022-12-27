import {
  FilterableField,
  IDField,
  CursorConnection,
} from '@nestjs-query/query-graphql';
import { Field, Float, ID, ObjectType } from '@nestjs/graphql';
import { AssetDto } from './asset.dto';

@ObjectType('Collection')
// @CursorConnection('assets', () => AssetDto)
export class CollectionDto {
  @IDField(() => ID)
  id: string;

  @FilterableField()
  name: string;

  @Field({ nullable: true })
  description?: string;

  @FilterableField()
  assetsCount: number;

  @FilterableField(() => Float)
  totalVolume: number;

  @FilterableField(() => Float)
  oneDayVolume: number;

  @FilterableField(() => Float)
  sevenDayVolume: number;

  @FilterableField(() => Float)
  sevenDayChange: number;

  @FilterableField(() => Float)
  averagePrice: number;

  @FilterableField(() => Float)
  floorPrice: number;

  @FilterableField(() => Float)
  numOwners: number;

  @FilterableField(() => Float)
  oneDayChange: number;

  @Field({ nullable: true })
  imageUrl?: string;

  @Field({ nullable: true })
  externalUrl?: string;

  @Field({ nullable: true })
  telegramUrl?: string;

  @Field({ nullable: true })
  twitterUsername?: string;

  @Field({ nullable: true })
  instagramUsername?: string;

  @Field({ nullable: true })
  mediumUsername?: string;
}
