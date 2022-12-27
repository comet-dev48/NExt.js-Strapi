import { HttpModule } from '@nestjs/axios';
import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { CollectionEntity } from 'src/database/entities/collection.entity';
import { MarketplaceEntity } from 'src/database/entities/marketplace.entity';
import { AssetEntity } from 'src/database/entities/asset.entity';
import { OpenSeaService } from 'src/external/services/opensea.service';
import { DappradarService } from 'src/external/services/dappradar.service';
import { AxieInfinityService } from 'src/external//services/axieinfinity.service';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      CollectionEntity,
      AssetEntity,
      MarketplaceEntity,
    ]),
    HttpModule,
  ],
  providers: [OpenSeaService, DappradarService, AxieInfinityService],
  exports: [OpenSeaService, DappradarService, AxieInfinityService],
})
export class ExternalModule {}
