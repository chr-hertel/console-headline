<?php

declare(strict_types=1);

namespace Stoffel\Console\Headline;

use Laminas\Text\Figlet\Figlet;
use Symfony\Component\Console\Color;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class HeadlineHelper
{
    private OutputInterface $output;
    private string $text = '';
    private Color $color;
    private array $options;

    private function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->color = new Color();

        $this->options = [
            'justification' => Figlet::JUSTIFICATION_CENTER,
            'outputWidth' => (new Terminal())->getWidth(),
        ];
    }

    public static function create(OutputInterface $output = null): self
    {
        return new self($output ?? new NullOutput());
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setColor(Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function setFigletOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function getHeight(): int
    {
        $renderedText = $this->render();

        return mb_substr_count($renderedText, PHP_EOL);
    }

    public function getWidth(): int
    {
        $renderedText = $this->render();

        return max(array_map('mb_strlen', explode(PHP_EOL, $renderedText)));
    }

    public function render(): string
    {
        return (new Figlet($this->options))->render($this->text);
    }

    public function write(): void
    {
        $this->output->write(
            $this->color->apply($this->render())
        );
    }
}
