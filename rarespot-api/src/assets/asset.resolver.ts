import { Resolver, Query, Args, Int, ID } from '@nestjs/graphql';
import { HttpService } from '@nestjs/axios';
import { firstValueFrom } from 'rxjs';
import { AssetDto } from 'src/dto/asset.dto';
import { AssetFilter } from 'src/enums/sort';

@Resolver(() => AssetDto)
export class AssetResolver {
  constructor(private readonly httpService: HttpService) {}
  private readonly API_URL = process.env.OPENSEA_API;

  @Query(() => [AssetDto], { name: 'assets' })
  async getAssets(
    @Args('id', { type: () => ID }) id: string,
    @Args('offset', { type: () => Int, defaultValue: 0 }) offset: number,
    @Args('limit', { type: () => Int, defaultValue: 9 }) limit: number,
    @Args('filter', {
      type: () => AssetFilter,
      defaultValue: AssetFilter.SALE_PRICE,
    })
    filter: AssetFilter,
  ) {
    const response = await firstValueFrom(
      this.httpService.get(
        `${this.API_URL}/assets?order_by=${filter}&order_direction=desc&offset=${offset}&limit=${limit}&collection=${id}`,
      ),
    );

    const mappedData = response.data.assets.map((res) => {
      const { image_url, last_sale, name, token_id, collection } = res;
      let eth = null;
      let usd = null;

      if (last_sale) {
        eth = `${Number(last_sale.total_price) / 1000000000000000000}`;
        usd = `${eth * Number(last_sale.payment_token.usd_price)}`;
      }
      const saleDate = last_sale ? last_sale.event_timestamp : null;

      return {
        id: `${id || Math.random()}-${token_id}`,
        tokenId: `#${token_id}`,
        imageUrl: image_url,
        name,
        eth,
        usd,
        saleDate,
        collection: collection.name
      };
    });

    return mappedData;
  }
}
