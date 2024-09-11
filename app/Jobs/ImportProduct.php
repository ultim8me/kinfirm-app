<?php

namespace App\Jobs;

use App\DataTransferObjects\ProductDto;
use App\DataTransferObjects\TagDto;
use App\Exceptions\JobException;
use App\Patterns\Controllers\WithTransactions;
use App\ServiceAccessors\WithProductService;
use App\ServiceAccessors\WithSizeService;
use App\ServiceAccessors\WithTagService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ImportProduct implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use Batchable;
    use WithTransactions;
    use WithProductService;
    use WithSizeService;
    use WithTagService;

    /**
     * Create a new job instance.
     */
    public function __construct(public ProductDto $productDto)
    {
        $this->afterCommit();
        $this->setWithTransactions(true);
    }

    public function tries(): int
    {
        return 3;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $methodResponse = $this->getProductService()->fetchBySku($this->productDto);
        if ($methodResponse->isFound()) {
            return;
        }

        if (!$this->productDto->getSizeId() && !$this->productDto->getSizeDto()?->getName()) {
            throw new JobException(sprintf("Product %s size is missing", $this->productDto->getSku() ?? ''));
        }

        $this->beginTransactions();

        if (!$this->productDto->getSizeId()) {

            $sizeDto = $this->productDto->getSizeDto();
            $methodResponse = $this->getSizeService()->fetchByName($sizeDto);

            if ($methodResponse->isFailure()) {
                $methodResponse = $this->getSizeService()->createSize($sizeDto);
                if ($methodResponse->isFailure()) {
                    throw new JobException($methodResponse);
                }
            }

            $this->productDto->setSizeDto($sizeDto);
        }

        /** @var TagDto $tagDto */
        foreach ($this->productDto->getTagDtos() as &$tagDto) {
            if (!$tagDto->getId()) {
                $methodResponse = $this->getTagService()->fetchByTitle($tagDto);
                if ($methodResponse->isFailure()) {
                    $methodResponse = $this->getTagService()->createTag($tagDto);
                    if ($methodResponse->isFailure()) {
                        throw new JobException($methodResponse);
                    }
                }
            }
        }

        $methodResponse = $this->getProductService()->createProduct($this->productDto);

        if ($methodResponse->isFailure()) {

            $this->rollbackTransactions();

            throw new Exception(sprintf(
                'Failed to import product: [%d] %s',
                $this->productDto->getId(),
                $this->productDto->getSku()
            ));
        }

        $this->commitTransactions();
    }
}
