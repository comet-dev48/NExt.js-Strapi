import { Injectable, Logger } from '@nestjs/common';
import { HttpService } from '@nestjs/axios';
import { firstValueFrom } from 'rxjs';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { AssetEntity } from 'src/database/entities/asset.entity';
import { CollectionEntity } from 'src/database/entities/collection.entity';

@Injectable()
export class AxieInfinityService {
  private LAST_OFFSET = 0;
  private readonly API_URL = process.env.AXIEINFINITY_API;
  private readonly INCREMENTED_OFFSET = 24;
  private readonly logger = new Logger(AxieInfinityService.name);

  constructor(
    private httpService: HttpService,
    @InjectRepository(CollectionEntity)
    private collectionRepo: Repository<CollectionEntity>,
    @InjectRepository(AssetEntity)
    private assetRepo: Repository<AssetEntity>,
  ) {}

  async getAssetsData(offset: number = 0) {
    this.logger.log(
      `Fetching assets for Axie Collection | offset: ${offset}`,
    );
    this.LAST_OFFSET = offset;

    try {
    const query = `query GetAxieBriefList {
        axies(
          from: ${offset}
          sort: PriceDesc
          size: 24
          owner: null
        ) {
          results {
              id
              name
              image
              auction {
                currentPrice
                currentPriceUSD
            }
          }
        }
      }`;

      const response = await firstValueFrom(
        this.httpService.post(`${this.API_URL}`, {
          query,
        }),
      );

      const results = response.data.data.axies.results;
      if (results.length === 0) return;

      const collection = await this.collectionRepo.findOne('axie');
      const mappedData = results.map((res) => {
        const {
          id,
          name,
          image,
          auction: { currentPrice, currentPriceUSD },
        } = res;

        const eth = `${Number(currentPrice) / 1000000000000000000}`;

        return {
          id: `axie-${id}`,
          name,
          eth,
          usd: currentPriceUSD,
          imageUrl: image,
          collection,
        };
      });
      await this.assetRepo.save(mappedData);
      await this.getAssetsData(offset + this.INCREMENTED_OFFSET);
    } catch (err) {
      this.logger.error(err);
      await this.getAssetsData(this.LAST_OFFSET);
    }
  }
}
