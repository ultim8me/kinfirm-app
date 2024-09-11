<?php

namespace App\Console\Commands\Product;

use App\Constants\DatabaseConnections;
use App\Constants\Queues;
use App\DataTransferObjects\ProductDto;
use App\DataTransferObjects\SizeDto;
use App\DataTransferObjects\TagDto;
use App\Jobs\ImportProduct;
use Cerbero\JsonParser\JsonParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class ImportProducts extends Command
{
    protected const PRODUCTS_URL = 'https://kinfirm.com/app/uploads/laravel-task/products.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jobs = [];

        foreach (new JsonParser(self::PRODUCTS_URL) as $key => $value) {

//            if ($key >= 5) {
//                break;
//            }

            $sizeDto = (new SizeDto())->setName($value['size'] ?? null);

            $tagDtos = [];
            foreach ($value['tags'] ?? [] as $tag) {
                $title = $tag['title'] ?? null;
                if ($title) {
                    $tagDtos[] = (new TagDto())->setTitle($tag['title']);
                }
            }

            $productDto = new ProductDto();
            $productDto
                ->setSku($value['sku'] ?? null)
                ->setPhotoUrl($value['photo'] ?? null)
                ->setDescription($value['description'] ?? null)
                ->setSizeDto($sizeDto)
                ->setTagDtos($tagDtos)
            ;

            $jobs[] = new ImportProduct($productDto);
        }

        Bus::batch(
            $jobs
        )
            ->onConnection(DatabaseConnections::CACHE_CONNECTION_NAME)
            ->onQueue(Queues::PRODUCTS)
            ->name("Products import")
            ->allowFailures()
            ->dispatch();
    }
}
