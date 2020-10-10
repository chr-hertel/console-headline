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

    private function __construct(OutputInterface $output = null)
    {
        $this->output = $output ?? new NullOutput();
        $this->color = new Color();

        $this->options = [
            'justification' => Figlet::JUSTIFICATION_CENTER,
            'outputWidth' => (new Terminal())->getWidth(),
        ];
    }

    public static function create(OutputInterface $output): self
    {
        return new self($output);
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

    public function write(): void
    {
        $figlet = new Figlet($this->options);

        $this->output->write(
            $this->color->apply($figlet->render($this->text))
        );
    }
}
