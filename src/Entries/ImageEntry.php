<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class ImageEntry extends Entry
{
    protected ?int $width = null;

    protected ?int $height = null;

    protected bool $rounded = false;

    protected bool $circular = false;

    protected ?string $alt = null;

    protected ?string $defaultImage = null;

    public function width(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function size(int $size): static
    {
        $this->width = $size;
        $this->height = $size;

        return $this;
    }

    public function rounded(bool $condition = true): static
    {
        $this->rounded = $condition;

        return $this;
    }

    public function circular(bool $condition = true): static
    {
        $this->circular = $condition;
        $this->rounded = $condition;

        return $this;
    }

    public function alt(string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public function defaultImage(string $url): static
    {
        $this->defaultImage = $url;

        return $this;
    }

    public function toLaraviltProps(): array
    {
        // Convert relative URLs to absolute URLs
        $imageUrl = $this->state;
        if ($imageUrl && ! filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            // If it's a relative URL, prepend the asset base URL
            $imageUrl = asset($imageUrl);
        }

        $defaultImageUrl = $this->defaultImage;
        if ($defaultImageUrl && ! filter_var($defaultImageUrl, FILTER_VALIDATE_URL)) {
            $defaultImageUrl = asset($defaultImageUrl);
        }

        return array_merge(parent::toLaraviltProps(), [
            'state' => $imageUrl,
            'width' => $this->width,
            'height' => $this->height,
            'rounded' => $this->rounded,
            'circular' => $this->circular,
            'alt' => $this->alt,
            'defaultImage' => $defaultImageUrl,
        ]);
    }
}
