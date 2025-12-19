<?php

namespace App\DTO;

class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $sequence,
        public int $sliderSequence,
        public bool $storeTab,
        public string $minImage,
        public array $sliderMedia,
        public array $children = [],
        public ?int $parentId = null
    ) {}

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'slug'            => $this->slug,
            'sequence'        => $this->sequence,
            'slider_sequence' => $this->sliderSequence,
            'store_tab'       => $this->storeTab,
            'min_image'       => $this->minImage,
            'slider_media'    => $this->sliderMedia,
            'children'        => array_map(fn ($c) => $c->toArray(), $this->children),
            'parent_id'       => $this->parentId,
        ];
    }
}
