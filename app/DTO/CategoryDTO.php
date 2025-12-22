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
        public bool $hasParent,
        public string $minImage,
        public array $sliderMedia,
        public array $children = [],
        public ?int $parentId = null,
        public ?string $short_description = null,
        public ?string $long_description = null,
        public ?string $long_description_bottom = null,
        public ?string $accepted_items = null,
        public bool $display_variant_price = false,
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
            'has_parent'     => $this->hasParent,
            'min_image'       => $this->minImage,
            'slider_media'    => $this->sliderMedia,
            'children'        => array_map(fn ($c) => $c->toArray(), $this->children),
            'parent_id'       => $this->parentId,
            'short_description'       => $this->short_description,
            'long_description'        => $this->long_description,
            'long_description_bottom' => $this->long_description_bottom,
            'accepted_items'          => $this->accepted_items,
            'display_variant_price'   => $this->display_variant_price,
        ];
    }
}
