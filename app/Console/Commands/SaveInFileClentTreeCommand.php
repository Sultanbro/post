<?php

namespace App\Console\Commands;

use App\Http\Resources\Client\TreeResource;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use Doctrine\DBAL\Exception;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SaveInFileClentTreeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientTreeSave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        parent::__construct();
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = json_encode( TreeResource::collection($this->departmentRepository->getParentDepartment()), true);

        return Storage::disk('local')->put('/clientTree/clientTree.json', $data);

    }
}
