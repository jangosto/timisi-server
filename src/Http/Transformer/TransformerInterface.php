<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

interface TransformerInterface
{
    public const string DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public const string DATE_FORMAT = 'Y-m-d';
}
