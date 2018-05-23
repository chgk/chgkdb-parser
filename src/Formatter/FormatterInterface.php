<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Formatter;

use Chgk\ChgkDb\Parser\Result\Package;

interface FormatterInterface
{
    public function format(Package $package, string $id = '') : string;
}
