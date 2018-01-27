<?php

class Game
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var array 起点
     */
    private $start;

    /**
     * @var array 终点
     */
    private $end;

    /**
     * @var int 触屏时间(ms)
     */
    private $touchTime;

    public function __construct()
    {
        $this->image = new Image();
    }

    public function run()
    {
        $this->setStart();
        $this->setEnd();
        $this->setTouchTime();
        $this->log();

        Utils::touch($this->touchTime);
    }

    private function setStart()
    {
        $colors = [];

        $w = $this->image->getWidth();
        $h = $this->image->getHeight();

        for ($x = $w - 1; $x >= 0; $x--) {
            for ($y = intval($h * .75); $y >= intval($h * .333); $y--) {
                $rgb = imagecolorat($this->image->getImg(), $x, $y);
                $r = $rgb >> 16 & 0xFF;
                $g = $rgb >> 8 & 0xFF;
                $b = $rgb & 0xFF;

                if ($this->mayStart($r, $g, $b)) {
                    $colors[] = [
                        'x' => $x, 'y' => $y
                    ];
                }
            }
        }

        $x = array_column($colors, 'x');
        $y = array_column($colors, 'y');

        $center = (min($x) + max($x)) / 2;

        $left = $center - Config::WIDTH_OF_CHESSMAN / 2;
        $right = $center + Config::WIDTH_OF_CHESSMAN / 2;

        $this->start = [
            'x' => $center,
            'y' => (min($y) + max($y)) / 2,
            'left' => $left,
            'right' => $right
        ];
    }

    private function mayStart($r, $g, $b): bool
    {
        return $r === 57 && $g === 57 && $b === 99;
    }

    private function setEnd()
    {
        $first = true;
        $flag = 0;

        // A、B、C三点坐标
        $A = [];
        $B = [];
        $C = [];

        $w = $this->image->getWidth();
        $h = $this->image->getHeight();

        for ($y = intval($h * .333); $y < $this->start['y']; $y++) {
            $data = [];

            for ($x = 0; $x < $w; $x++) {
                if ($x >= $this->start['left'] && $x <= $this->start['right']) {
                    continue;
                }

                $rgb = imagecolorat($this->image->getImg(), $x, $y);
                $r = $rgb >> 16 & 0xFF;
                $g = $rgb >> 8 & 0xFF;
                $b = $rgb & 0xFF;

                $data[] = [
                    'rgb' => $rgb,
                    'r' => $r, 'g' => $g, 'b' => $b,
                    'x' => $x, 'y' => $y
                ];
            }

            // 取出rgb列
            $rgbColumn = array_column($data, 'rgb');
            // 统计颜色出现次数
            $rgbCountValues = array_count_values($rgbColumn);
            // 按照颜色出现次数多少排序 (多 -> 少)
            arsort($rgbCountValues);

            // 找到颜色出现最多的
            $color = Utils::search($data, key($rgbCountValues));

            // 找到与出现最多的颜色区别较大的颜色
            $result = Utils::findDiff($data, [
                'r' => $color['r'],
                'g' => $color['g'],
                'b' => $color['b'],
            ]);

            if ($result && $first) {
                $_x = array_column($result, 'x');
                $_y = array_column($result, 'y');

                // 找到顶点A
                $A = ['x' => (min($_x) + max($_x)) / 2, 'y' => (min($_y) + max($_y)) / 2];

                // 横坐标范围
                $B = ['x' => min($_x), 'y' => $A['y']];
                $C = ['x' => max($_x), 'y' => $A['y']];

                $first = false;

            } elseif ($result && !$first) {

                $_x = array_column($result, 'x');
                $min = min($_x);
                $max = max($_x);

                if ($min < $B['x'] && $max > $C['x']) {
                    $flag = 0;
                    // 找到最小的横坐标与最大的横坐标
                    $B = ['x' => $min, 'y' => $y];
                    $C = ['x' => $max, 'y' => $y];
                } else {
                    $flag++;
                }

                if ($flag > 3) {
                    break;
                }
            }

            unset($data);
        }

        $this->end = [
            'x' => $A['x'],
            'y' => $B['y'] - 3
        ];

    }

    private function setTouchTime()
    {
        $distance = sqrt(($this->end['x'] - $this->start['x']) ** 2 + ($this->end['y'] - $this->start['y']) ** 2);

        $this->touchTime = intval($distance * Config::MS_PER_PIXEL);
    }

    /**
     * 调试模式打开时在截图中记录起点和终点的位置
     */
    private function log()
    {
        if (Config::DEBUG) {
            $this->image
                ->fill($this->start['x'], $this->start['y'])
                ->fill($this->end['x'], $this->end['y'])
                ->save();
        }
    }
}
