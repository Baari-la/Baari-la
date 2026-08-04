<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Company\Identity\CanonicalCompanyLinkService;
use Illuminate\Console\Command;
use Throwable;

class LinkCanonicalCompaniesCommand extends Command
{
    /**
     * The console command signature.
     */
    protected $signature = 'canonical:link-companies';

    /**
     * The console command description.
     */
    protected $description = 'Link legacy companies and users to Canonical Company Identity.';

    /**
     * Execute the console command.
     */
    public function handle(
        CanonicalCompanyLinkService $linkService
    ): int {

        $this->newLine();

        $this->info('===============================================');
        $this->info(' DIGESTEX Canonical Company Link');
        $this->info('===============================================');

        try {

            $linkedCompanies = $linkService->linkAllCompanies();

            $linkedUsers = $linkService->linkClaimedCompanies();

            $this->newLine();

            $this->info('Synchronization completed successfully.');

            $this->table(
                ['Item', 'Total'],
                [
                    ['Companies Linked', $linkedCompanies],
                    ['Claimed Users Linked', $linkedUsers],
                ]
            );

            $this->newLine();

            $this->info('Canonical Company Link completed.');

            return self::SUCCESS;

        } catch (Throwable $exception) {

            $this->newLine();

            $this->error('Canonical Company Link failed.');

            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}