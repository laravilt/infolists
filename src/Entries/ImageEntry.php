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

    protected bool $stacked = false;

    protected ?int $limit = null;

    protected ?int $ring = null;

    protected ?int $overlap = null;

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

    /**
     * Stack multiple images with overlap effect.
     */
    public function stacked(bool $condition = true): static
    {
        $this->stacked = $condition;

        return $this;
    }

    /**
     * Limit the number of images shown.
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Set the ring width for stacked images.
     */
    public function ring(int $ring): static
    {
        $this->ring = $ring;

        return $this;
    }

    /**
     * Set the overlap amount for stacked images.
     */
    public function overlap(int $overlap): static
    {
        $this->overlap = $overlap;

        return $this;
    }

    /**
     * Convert a single URL to absolute if it's relative.
     */
    protected function convertToAbsoluteUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return asset($url);
    }

    /**
     * Process image URLs - handles both single string and array of URLs.
     */
    protected function processImageUrls(mixed $state): mixed
    {
        if (is_array($state)) {
            return array_map(fn ($url) => $this->convertToAbsoluteUrl($url), $state);
        }

        if (is_string($state)) {
            return $this->convertToAbsoluteUrl($state);
        }

        return $state;
    }

    public function toLaraviltProps(): array
    {
        // Convert relative URLs to absolute URLs (handles both string and array)
        $imageUrl = $this->processImageUrls($this->state);

        $defaultImageUrl = $this->convertToAbsoluteUrl($this->defaultImage);

        return array_merge(parent::toLaraviltProps(), [
            'state' => $imageUrl,
            'width' => $this->width,
            'height' => $this->height,
            'rounded' => $this->rounded,
            'circular' => $this->circular,
            'alt' => $this->alt,
            'defaultImage' => $defaultImageUrl,
            'stacked' => $this->stacked,
            'limit' => $this->limit,
            'ring' => $this->ring,
            'overlap' => $this->overlap,
        ]);
    }
}
