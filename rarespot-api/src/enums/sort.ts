import { registerEnumType } from '@nestjs/graphql';

export enum AssetFilter {
  SALE_COUNT = 'sale_count',
  SALE_PRICE = 'sale_price',
}

registerEnumType(AssetFilter, { name: 'AssetFilter' });
