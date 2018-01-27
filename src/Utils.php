<?php

class Utils
{
    public static function getCapture(): string
    {
        $cmd = implode(' ', [
            Config::ADB, 'shell', 'screencap', '-p', '/sdcard/' . Config::IMAGE_NAME,
            '&',
            Config::ADB, 'pull', '/sdcard/' . Config::IMAGE_NAME, Config::TEMP_PATH
        ]);

        `$cmd`;

        return Config::TEMP_PATH . '/' . Config::IMAGE_NAME;
    }

    public static function touch(int $time)
    {
        $x = mt_rand(100, 300);
        $y = mt_rand(100, 300);

        $cmd = implode(' ', [
            Config::ADB, 'shell', 'input', 'swipe', $x, $y, $x, $y, $time
        ]);

        `$cmd`;
    }

    public static function search(array $array, int $key): array
    {
        foreach ($array as $item) {
            if ($item['rgb'] === $key) {
                return $item;
            }
        }

        return [];
    }

    public static function findDiff(array $array, array $key): array
    {
        $result = [];

        foreach ($array as $item) {
            if (abs($item['r'] - $key['r']) > 20
                || abs($item['g'] - $key['g']) > 20
                || abs($item['b'] - $key['b']) > 20) {

                $result[] = $item;
            }
        }

        return $result;
    }
}
