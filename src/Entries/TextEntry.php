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

    protected ?array $numericFormat = null;

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
     * Format the value as a number with locale-aware formatting.
     *
     * @param  int|null  $decimalPlaces  Number of decimal places
     * @param  string|null  $decimalSeparator  Custom decimal separator
     * @param  string|null  $thousandsSeparator  Custom thousands separator
     * @param  string|null  $locale  Locale for number formatting (e.g., 'en', 'de', 'fr')
     */
    public function numeric(
        ?int $decimalPlaces = null,
        ?string $decimalSeparator = null,
        ?string $thousandsSeparator = null,
        ?string $locale = null,
    ): static {
        $this->numericFormat = [
            'decimalPlaces' => $decimalPlaces,
            'decimalSeparator' => $decimalSeparator,
            'thousandsSeparator' => $thousandsSeparator,
            'locale' => $locale,
        ];

        $this->formatStateUsing(function ($state) use ($decimalPlaces, $decimalSeparator, $thousandsSeparator, $locale) {
            if ($state === null || $state === '') {
                return null;
            }

            if (! is_numeric($state)) {
                return $state;
            }

            $numericValue = (float) $state;

            // Use locale-based formatting if locale is provided
            if ($locale !== null) {
                $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
                if ($decimalPlaces !== null) {
                    $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $decimalPlaces);
                    $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimalPlaces);
                }

                return $formatter->format($numericValue);
            }

            // Use custom separators or defaults
            $decimals = $decimalPlaces ?? (floor($numericValue) == $numericValue ? 0 : 2);
            $dec = $decimalSeparator ?? '.';
            $thousands = $thousandsSeparator ?? ',';

            return number_format($numericValue, $decimals, $dec, $thousands);
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
            'numericFormat' => $this->numericFormat,
        ]);
    }
}
