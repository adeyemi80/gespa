<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use RuntimeException;

class ThermalPrinterService
{
    public function __construct(
        protected string $printer = 'Xprinter_POS58'
    ) {
    }

    public function print(string $content): string
    {
        $process = new Process([
            'lp',
            '-d',
            $this->printer,
        ]);

        $process->setInput($content);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput())
            );
        }

        return trim($process->getOutput());
    }
}