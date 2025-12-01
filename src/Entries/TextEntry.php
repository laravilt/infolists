<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class TextEntry extends Entry
{
    protected ?int $limit = null;

    protected bool $wrap = false;

    protected bool $markdown = false;

    protected bool $html = false;

    protected ?string $prefix = null;

    protected ?string $suffix = null;

    protected bool $badge = false;

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

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'limit' => $this->limit,
            'wrap' => $this->wrap,
            'markdown' => $this->markdown,
            'html' => $this->html,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'badge' => $this->badge,
        ]);
    }
}
