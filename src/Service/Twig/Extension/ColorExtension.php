<?php

declare(strict_types=1);

namespace Infrastructure\Service\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ColorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('mix_color', [$this, 'mixColor']),
        ];
    }

    public function mixColor(string $color1, string $color2, int $percentage): string
    {
        // Quitar el símbolo '#' si está presente
        $color1 = ltrim($color1, '#');
        $color2 = ltrim($color2, '#');

        // Convertir los colores de hexadecimal a RGB
        $r1 = hexdec(substr($color1, 0, 2));
        $g1 = hexdec(substr($color1, 2, 2));
        $b1 = hexdec(substr($color1, 4, 2));

        $r2 = hexdec(substr($color2, 0, 2));
        $g2 = hexdec(substr($color2, 2, 2));
        $b2 = hexdec(substr($color2, 4, 2));

        // Convertir el porcentaje a decimal
        $p = $percentage / 100.0;
        $q = 1 - $p;

        // Calcular cada componente del color resultante
        $r = round($r1 * $p + $r2 * $q);
        $g = round($g1 * $p + $g2 * $q);
        $b = round($b1 * $p + $b2 * $q);

        return \sprintf('#%02X%02X%02X', $r, $g, $b);
    }
}
