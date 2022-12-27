import { Module } from '@nestjs/common';
import { ExternalModule } from 'src/external/external.module';
import { JobsService } from 'src/jobs/jobs.service';

@Module({
  imports: [ExternalModule],
  providers: [JobsService],
})
export class JobsModule {}
