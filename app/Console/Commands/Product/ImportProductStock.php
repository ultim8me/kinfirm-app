<?php

namespace App\Console\Commands\Product;

use App\Constants\DatabaseConnections;
use App\Constants\Queues;
use App\DataTransferObjects\CityDto;
use App\DataTransferObjects\ProductDto;
use App\DataTransferObjects\StockDto;
use App\Jobs\ImportProductStock as ImportProductStockJob;
use Cerbero\JsonParser\JsonParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class ImportProductStock extends Command
{
    protected const STOCK_URL = 'https://kinfirm.com/app/uploads/laravel-task/stocks.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-product-stock';

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

        foreach (new JsonParser(self::STOCK_URL) as $key => $value) {

            $stockDto = (new StockDto())
                ->setCityDto((new CityDto())->setName($value['city'] ?? null))
                ->setProductDto((new ProductDto())->setSku($value['sku'] ?? null))
                ->setQuantity($value['stock'] ?? null)
            ;

            $jobs[] = new ImportProductStockJob($stockDto);
        }

        Bus::batch(
            $jobs
        )
            ->onConnection(DatabaseConnections::CACHE_CONNECTION_NAME)
            ->onQueue(Queues::STOCK)
            ->name("Products stock import")
            ->allowFailures()
            ->dispatch();
    }
}
