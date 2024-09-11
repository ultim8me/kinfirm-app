<?php

namespace App\Jobs;

use App\DataTransferObjects\StockDto;
use App\Exceptions\JobException;
use App\Patterns\Controllers\WithTransactions;
use App\ServiceAccessors\WithCityService;
use App\ServiceAccessors\WithProductService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ImportProductStock implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use Batchable;
    use WithTransactions;
    use WithCityService;
    use WithProductService;

    /**
     * Create a new job instance.
     */
    public function __construct(public StockDto $stockDto)
    {
        $this->afterCommit();
        $this->setWithTransactions(true);
    }

    public function tries(): int
    {
        return 1;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (is_null($this->stockDto->getQuantity())
            || is_null($this->stockDto->getCityDto()->getName())
            || is_null($this->stockDto->getProductDto()->getSku())
        ) {
            $this->fail(sprintf("Product %s stock data is missing", $this->stockDto->getProductDto()->getSku() ?? ''));
            return;
        }

        if (!$this->stockDto->getProductDto()->getId()) {

            $productDto = $this->stockDto->getProductDto();
            $methodResponse = $this->getProductService()->fetchBySku($productDto);

            if ($methodResponse->isFailure()) {
                $this->fail(sprintf("Product %s not found", $productDto->getSku() ?? ''));
                return;
            }

            $this->stockDto->setProductDto($productDto);
        }

        $this->beginTransactions();

        if (!$this->stockDto->getCityDto()->getId()) {

            $cityDto = $this->stockDto->getCityDto();
            $methodResponse = $this->getCityService()->fetchByName($cityDto);

            if ($methodResponse->isFailure()) {
                $methodResponse = $this->getCityService()->createCity($cityDto);
                if ($methodResponse->isFailure()) {
                    throw new JobException($methodResponse);
                }
            }

            $this->stockDto->setCityDto($cityDto);
        }

        $methodResponse = $this->getProductService()->addProductStock($this->stockDto->getProductDto(), $this->stockDto);

        if ($methodResponse->isFailure()) {

            $this->rollbackTransactions();

            throw new Exception(sprintf(
                'Failed to import product stock: [%d] %s',
                $this->stockDto->getProductDto()->getId(),
                $this->stockDto->getProductDto()->getSku(),
            ));
        }

        $this->commitTransactions();
    }
}
