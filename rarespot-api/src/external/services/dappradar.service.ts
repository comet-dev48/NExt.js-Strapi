import { Injectable, Logger } from '@nestjs/common';
import { HttpService } from '@nestjs/axios';
import { firstValueFrom } from 'rxjs';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { MarketplaceEntity } from 'src/database/entities/marketplace.entity';
import { convertToKebabCase } from 'src/utils';

@Injectable()
export class DappradarService {
  private readonly DAPPRADAR_API_URL = process.env.DAPPRADAR_API;
  private readonly MARKETPLACE_PAGE_LIMIT = 25;
  private readonly MARKETPLACE_CURRENCY = 'USD';
  private readonly MARKETPLACE_SORT = 'volume_fiat';
  private readonly MARKETPLACE_ORDER = 'desc';
  private readonly logger = new Logger(DappradarService.name);

  constructor(
    private httpService: HttpService,
    @InjectRepository(MarketplaceEntity)
    private marketplaceRepo: Repository<MarketplaceEntity>,
  ) {}

  async getMarkerplaceData(page: number = 1): Promise<void> {
    this.logger.log(`Fetching data for page: ${page}`);

    try {
      const reponse = await firstValueFrom(
        this.httpService.get(
          `${this.DAPPRADAR_API_URL}/marketplace/day?limit=${this.MARKETPLACE_PAGE_LIMIT}&page=${page}&currency=${this.MARKETPLACE_CURRENCY}&sort=${this.MARKETPLACE_SORT}&order=${this.MARKETPLACE_ORDER}`,
          {
            headers: {
              'User-Agent': '/',
            },
          },
        ),
      );

      const data: Array<any> = reponse.data.results;
      if (data.length === 0) return;

      const mappedData = data.map((res) => {
        const {
          name,
          logo,
          volume_fiat,
          volume_fiat_change,
          traders,
          traders_change,
        } = res;

        return {
          id: this.getMarketplaceSlug(name),
          name,
          logo,
          volume: volume_fiat,
          volumeChange: volume_fiat_change,
          traders: +traders,
          tradersChange: +traders_change,
        };
      });

      await this.marketplaceRepo.save(mappedData);
      await this.getMarkerplaceData(page + 1);
    } catch (err) {
      this.logger.error(err.toJSON().message);
    }
  }

  private getMarketplaceSlug(name: string): string {
    return convertToKebabCase(name);
  }
}
