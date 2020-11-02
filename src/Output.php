<?php

declare(strict_types=1);

namespace Isaev\WatchLog;

class Output
{
    public static $colorsMap = [
        'default' => 29,
        'red'     => 31,
        'green'   => 32,
        'yellow'  => 33,
        'cyan'    => 36
    ];

    // ########################################

    public function print(string $str, string $color = 'default'): void
    {
        echo $this->format($str, $color);
    }

    public function printLn(string $str, string $color = 'default'): void
    {
        $this->print($str . PHP_EOL, $color);
    }

    public function printLn2(string $str, string $color = 'default'): void
    {
        $this->print($str . PHP_EOL . PHP_EOL, $color);
    }

    // ########################################

    public function format(string $str, string $color = 'default'): string
    {
        $colorMark = self::$colorsMap[$color] ?? self::$colorsMap['default'];
        return "\033[{$colorMark}m{$str}\033[0m";
    }

    // ########################################
}
