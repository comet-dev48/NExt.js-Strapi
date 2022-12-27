
import { FilterableField, IDField } from '@nestjs-query/query-graphql';
import { Field, Float, ID, ObjectType } from '@nestjs/graphql';

@ObjectType('Marketplace')
export class MarketplaceDto {
  @IDField(() => ID)
  id: string;

  @Field()
  name: string;

  @Field({ nullable: true })
  logo?: string;

  @FilterableField()
  volume: number;

  @Field(() => Float)
  volumeChange: number;

  @Field()
  traders: number;

  @Field(() => Float)
  tradersChange: number;
}
