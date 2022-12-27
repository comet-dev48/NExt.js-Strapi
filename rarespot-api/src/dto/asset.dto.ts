import { FilterableField, IDField } from '@nestjs-query/query-graphql';
import { Field, Float, ID, ObjectType } from '@nestjs/graphql';

@ObjectType('Asset')
export class AssetDto {
  @IDField(() => ID)
  id: string;

  @Field()
  tokenId: string;

  @Field({ nullable: true })
  name: string;

  @FilterableField(() => Float, { nullable: true })
  usd: string;

  @FilterableField(() => Float, { nullable: true })
  eth: string;

  @FilterableField({ nullable: true })
  saleDate: string;

  @Field({ nullable: true })
  imageUrl?: string;

  @Field()
  collection: string;
}
