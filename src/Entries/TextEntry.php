<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class TextEntry extends Entry
{
    protected ?int $limit = null;

    protected bool $wrap = false;

    protected bool $markdown = false;

    protected bool $html = false;

    protected bool $prose = false;

    protected ?string $prefix = null;

    protected ?string $suffix = null;

    protected bool $badge = false;

    protected ?string $weight = null;

    protected ?string $textSize = null;

    protected ?string $separator = null;

    protected ?string $url = null;

    protected bool $urlOpenInNewTab = false;

    protected bool $copyable = false;

    protected ?string $copyMessage = null;

    protected bool $strikethrough = false;

    public function badge(bool $condition = true): static
    {
        $this->badge = $condition;

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function wrap(bool $condition = true): static
    {
        $this->wrap = $condition;

        return $this;
    }

    public function markdown(bool $condition = true): static
    {
        $this->markdown = $condition;

        return $this;
    }

    public function html(bool $condition = true): static
    {
        $this->html = $condition;

        return $this;
    }

    /**
     * Display as prose text with proper typography styling
     */
    public function prose(bool $condition = true): static
    {
        $this->prose = $condition;

        return $this;
    }

    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function suffix(string $suffix): static
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function weight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function size(?string $size): static
    {
        $this->textSize = $size;

        return $this;
    }

    public function money(string $currency = 'USD', bool $divideBy = false): static
    {
        $this->formatStateUsing(function ($state) use ($currency, $divideBy) {
            if ($state === null || $state === '') {
                return;
            }

            $numericValue = is_numeric($state) ? (float) $state : 0;

            if ($divideBy) {
                $numericValue = $numericValue / 100;
            }

            return $currency.' '.number_format($numericValue, 2);
        });

        return $this;
    }

    public function date(string $format = 'M d, Y'): static
    {
        $this->formatStateUsing(function ($state) use ($format) {
            if (! $state) {
                return;
            }

            return \Carbon\Carbon::parse($state)->format($format);
        });

        return $this;
    }

    public function dateTime(string $format = 'M d, Y H:i'): static
    {
        return $this->date($format);
    }

    public function since(): static
    {
        $this->formatStateUsing(function ($state) {
            if (! $state) {
                return;
            }

            return \Carbon\Carbon::parse($state)->diffForHumans();
        });

        return $this;
    }

    /**
     * Set separator for array values (FilamentPHP v4 compatibility).
     */
    public function separator(?string $separator = ', '): static
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Set URL for this entry (FilamentPHP v4 compatibility).
     */
    public function url(?string $url, bool $openInNewTab = false): static
    {
        $this->url = $url;
        $this->urlOpenInNewTab = $openInNewTab;

        return $this;
    }

    /**
     * Open URL in new tab (FilamentPHP v4 compatibility).
     */
    public function openUrlInNewTab(bool $condition = true): static
    {
        $this->urlOpenInNewTab = $condition;

        return $this;
    }

    /**
     * Make the entry copyable (FilamentPHP v4 compatibility).
     */
    public function copyable(bool $condition = true): static
    {
        $this->copyable = $condition;

        return $this;
    }

    /**
     * Set the copy success message (FilamentPHP v4 compatibility).
     */
    public function copyMessage(?string $message): static
    {
        $this->copyMessage = $message;

        return $this;
    }

    /**
     * Display text with strikethrough styling.
     */
    public function strikethrough(bool $condition = true): static
    {
        $this->strikethrough = $condition;

        return $this;
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'limit' => $this->limit,
            'wrap' => $this->wrap,
            'markdown' => $this->markdown,
            'html' => $this->html,
            'prose' => $this->prose,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'badge' => $this->badge,
            'weight' => $this->weight,
            'size' => $this->textSize,
            'separator' => $this->separator,
            'url' => $this->url,
            'openUrlInNewTab' => $this->urlOpenInNewTab,
            'copyable' => $this->copyable,
            'copyMessage' => $this->copyMessage,
            'strikethrough' => $this->strikethrough,
        ]);
    }
}
