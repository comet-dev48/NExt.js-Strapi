import { Injectable } from '@nestjs/common';
import { Cron, CronExpression } from '@nestjs/schedule';
import { AxieInfinityService } from 'src/external/services/axieinfinity.service';
import { DappradarService } from 'src/external/services/dappradar.service';
import { OpenSeaService } from 'src/external/services/opensea.service';

@Injectable()
export class JobsService {
  constructor(
    private openSeaService: OpenSeaService,
    private dappradarService: DappradarService,
    private axieInfinityService: AxieInfinityService,
  ) {}

  @Cron(CronExpression.EVERY_10_MINUTES)
  async getOpenSeaCollectionData() {
    await this.openSeaService.getCollectionData();
  }

  // @Cron(CronExpression.EVERY_HOUR)
  // async gerOpenSeaAssetData() {
  //   await this.openSeaService.getAssetsData();
  // }

  // @Cron(CronExpression.EVERY_10_MINUTES)
  // async getAxieInfinityAssets() {
  //   await this.axieInfinityService.getAssetsData();
  // }

  @Cron(CronExpression.EVERY_5_MINUTES)
  async getDappradarMarketplaceData() {
    await this.dappradarService.getMarkerplaceData();
  }
}
