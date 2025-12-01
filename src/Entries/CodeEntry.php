<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class CodeEntry extends Entry
{
    protected string $language = 'plaintext';

    protected bool $copyable = true;

    protected ?int $maxHeight = null;

    protected bool $lineNumbers = true;

    public function language(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function maxHeight(int $height): static
    {
        $this->maxHeight = $height;

        return $this;
    }

    public function lineNumbers(bool $condition = true): static
    {
        $this->lineNumbers = $condition;

        return $this;
    }

    public function json(): static
    {
        return $this->language('json');
    }

    public function php(): static
    {
        return $this->language('php');
    }

    public function javascript(): static
    {
        return $this->language('javascript');
    }

    public function typescript(): static
    {
        return $this->language('typescript');
    }

    public function python(): static
    {
        return $this->language('python');
    }

    public function sql(): static
    {
        return $this->language('sql');
    }

    public function yaml(): static
    {
        return $this->language('yaml');
    }

    public function html(): static
    {
        return $this->language('html');
    }

    public function css(): static
    {
        return $this->language('css');
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'language' => $this->language,
            'maxHeight' => $this->maxHeight,
            'lineNumbers' => $this->lineNumbers,
        ]);
    }
}
