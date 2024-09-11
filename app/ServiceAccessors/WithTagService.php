<?php
namespace App\ServiceAccessors;

use App\Services\TagService;

trait WithTagService
{
    /**
     * @internal This value is managed by getTagService.
     * Direct access is discouraged.
     *
     * @var ?TagService An TagService object.
     */
    private ?TagService $tagService = null;

    /**
     * Accessor method to get the TagService value.
     *
     * @return TagService An TagService object.
     */
    public function getTagService() : TagService
    {
        return $this->tagService ?? app(TagService::class);
    }
}
