<?php

namespace App\DataTransferObjects;

trait WithTags
{
    /** @var TagDto[]|null $tagDtos */
    protected ?array $tagDtos = [];

    public function getTagDtos(): ?array
    {
        return $this->tagDtos;
    }

    public function setTagDtos(?array $tagDtos): static
    {
        $this->tagDtos = $tagDtos;
        return $this;
    }

    public function getTagIds(): ?array
    {
        $tagIds = [];
        foreach ($this->getTagDtos() ?? [] as $tagDto) {
            $tagIds[] = $tagDto->getId();
        }

        return $tagIds;
    }

}
