import { Injectable, Logger } from '@nestjs/common';
import { HttpService } from '@nestjs/axios';
import { firstValueFrom } from 'rxjs';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { CollectionEntity } from 'src/database/entities/collection.entity';
import { AssetReponse, Collection } from 'src/interfaces/external.interface';
import { AssetEntity } from 'src/database/entities/asset.entity';

@Injectable()
export class OpenSeaService {
  private readonly MAX_OFFSET = 50250;
  private readonly COLLECTION_INCREMENTED_OFFSET = 250;
  private readonly ASSET_INCREMENTED_OFFSET = 20;
  private LAST_COLLECTION_OFFSET = 0;
  private LAST_ASSET_OFFSET = 0;
  private readonly API_URL = process.env.OPENSEA_API;
  private readonly logger = new Logger(OpenSeaService.name);

  constructor(
    private httpService: HttpService,
    @InjectRepository(CollectionEntity)
    private collectionRepo: Repository<CollectionEntity>,
    @InjectRepository(AssetEntity)
    private assetRepo: Repository<AssetEntity>,
  ) {}

  private async fetchCollections(
    address: string,
    offset: number = 0,
  ): Promise<void> {
    if (offset === this.MAX_OFFSET) return;

    this.logger.log(
      `Fetching COLLECTIONS at address: ${address} | offset: ${offset}`,
    );
    this.LAST_COLLECTION_OFFSET = offset;

    try {
      const response = await firstValueFrom(
        this.httpService.get<Collection[]>(
          `${this.API_URL}/collections?asset_owner=${address}&offset=${offset}`,
        ),
      );

      const collections = response.data;

      if (collections.length === 0) return;

      const mappedData = collections
        .map((res) => {
          const {
            name,
            slug,
            image_url,
            description,
            external_url,
            telegram_url,
            twitter_username,
            instagram_username,
            medium_username,
            stats: {
              total_volume,
              seven_day_volume,
              seven_day_change,
              average_price,
              floor_price,
              num_owners,
              total_supply,
              one_day_volume,
              one_day_change,
            },
          } = res;

          return {
            id: slug,
            name,
            description,
            assetsCount: total_supply,
            totalVolume: total_volume,
            oneDayVolume: one_day_volume,
            floorPrice: floor_price,
            numOwners: num_owners,
            sevenDayVolume: seven_day_volume,
            sevenDayChange: seven_day_change,
            averagePrice: average_price,
            oneDayChange: one_day_change,
            imageUrl: image_url,
            externalUrl: external_url,
            telegramUrl: telegram_url,
            twitterUsername: twitter_username,
            instagramUsername: instagram_username,
            mediumUsername: medium_username,
          };
        })
        .filter((i) => i.totalVolume > 0);

      await this.collectionRepo.save(mappedData);
      await this.fetchCollections(
        address,
        offset + this.COLLECTION_INCREMENTED_OFFSET,
      );
    } catch (err) {
      // At random points the OpenSea server will throw 500 error.
      // To continue from the last offset, we are recursively calling
      // the API from that last offset
      this.logger.error(err.toJSON().message);
      this.fetchCollections(address, this.LAST_COLLECTION_OFFSET);
    }
  }

  private async fetchAssets(id: string, offset: number = 0) {
    this.logger.log(`Fetching ASSETS for id: ${id} | offset: ${offset}`);
    this.LAST_ASSET_OFFSET = offset;

    try {
      const response = await firstValueFrom(
        this.httpService.get<AssetReponse>(
          `${this.API_URL}/assets?order_by=sale_count&order_direction=desc&offset=${offset}&limit=20&collection=${id}`,
        ),
      );
      const assets = response.data.assets;

      if (assets.length === 0) return;
      const collection = await this.collectionRepo.findOne(id);

      const mappedData = assets.map((res) => {
        const { image_url, last_sale, name, token_id } = res;
        let eth = null;
        let usd = null;

        if (last_sale) {
          eth = `${Number(last_sale.total_price) / 1000000000000000000}`;
          usd = `${eth * Number(last_sale.payment_token.usd_price)}`;
        }
        const saleDate = last_sale ? last_sale.event_timestamp : null;

        return {
          id: `${id}-${token_id}`,
          imageUrl: image_url,
          name,
          eth,
          usd,
          saleDate,
          collection,
        };
      });

      await this.assetRepo.save(mappedData);
      await this.fetchAssets(id, offset + this.ASSET_INCREMENTED_OFFSET);
    } catch (err) {
      // At random points the OpenSea server will throw 500 error.
      // To continue from the last offset, we are recursively calling
      // the API from that last offset
      this.logger.error(err);
      await this.fetchAssets(id, this.LAST_ASSET_OFFSET);
    }
  }

  async getCollectionData() {
    const addresses = [
      '0x37236cd05b34cc79d3715af2383e96dd7443dcf1',
      '0x0000000000000000000000000000000000000000',
    ];

    for (let i = 0; i < addresses.length; i++) {
      await this.fetchCollections(addresses[i]);
    }
  }

  async getAssetsData() {
    const entities = await this.collectionRepo.find();
    const ids = entities.map((i) => i.id).filter((i) => i !== 'axie');

    for (let i = 0; i < ids.length; i++) {
      await this.fetchAssets(ids[i]);
    }
  }
}
