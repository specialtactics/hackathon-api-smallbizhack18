<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use App\Services\InstagramService;
use Illuminate\Console\Command;

class ProcessCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process campaigns, getting photos from instagram';

    protected $instagramService;

    /**
     * Create a new command instance.
     * ProcessCampaigns constructor.
     * @param InstagramService $instagramService
     */
    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->instagramService->processCampaigns();
    }
}
