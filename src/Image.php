<?php

class Image
{
    private $img;

    private $width;

    private $height;

    public function __construct()
    {
        $this->img = imagecreatefrompng(Utils::getCapture());

        $this->width = imagesx($this->img);
        $this->height = imagesy($this->img);
    }

    public function __destruct()
    {
        if ($this->img) {
            imagedestroy($this->img);
        }
    }

    public function getImg()
    {
        return $this->img;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function fill($x, $y)
    {
        $color = 0xFF0000;

        imagefilledellipse($this->img, intval($x), intval($y), 10, 10, $color);

        return $this;
    }

    public function save()
    {
        global $i;

        imagepng($this->img, Config::TEMP_PATH . "/debug_{$i}.png");
    }
}
