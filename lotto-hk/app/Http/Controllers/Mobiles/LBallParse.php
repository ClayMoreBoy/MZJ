<?php
/**
 * Created by PhpStorm.
 * User: maozhijun
 * Date: 17/11/30
 * Time: 15:12
 */

namespace App\Http\Controllers\Mobiles;


use App\Http\Controllers\Spider\Calendar;

trait LBallParse
{
    var $zodiacs = ["鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊", "猴", "鸡", "狗", "猪"];

    var $red_ball = [1, 2, 7, 8, 12, 13, 18, 19, 23, 24, 29, 30, 34, 35, 40, 45, 46];
    var $blue_ball = [3, 4, 9, 10, 14, 15, 20, 25, 26, 31, 36, 37, 41, 42, 47, 48];
    var $green_ball = [5, 6, 11, 16, 17, 21, 22, 27, 28, 32, 33, 38, 39, 43, 44, 49];

    var $jin_ball = [10, 11, 18, 19, 26, 27, 40, 41, 48, 49];
    var $mu_ball = [1, 8, 9, 22, 23, 30, 31, 38, 39];
    var $shui_ball = [6, 7, 14, 15, 28, 29, 36, 37, 44, 45];
    var $huo_ball = [2, 3, 16, 17, 24, 25, 32, 33, 46, 47];
    var $tu_ball = [4, 5, 12, 13, 20, 21, 34, 35, 42, 43];

    var $first_zodiac = '';

    var $first_zodiac_index = 0;

    var $zodiacs_ball = [];

    var $num_attr = [];

    private function initBalls()
    {
        $cal = new Calendar();
        $this->first_zodiac = $cal->Cal()['zodiac'];
        $this->first_zodiac_index = array_search($this->first_zodiac, $this->zodiacs);
        for ($i = 0; $i < 49; $i++) {
            $index = (60 + $this->first_zodiac_index - $i) % 12;
            $this->zodiacs_ball[$this->zodiacs[$index]][] = $i + 1;

            $this->num_attr[($i + 1)]['num'] = $i + 1;
            $this->num_attr[($i + 1)]['zodiacs'] = $this->zodiacs[$index];
            if (in_array($i + 1, $this->red_ball)) {
                $this->num_attr[($i + 1)]['color'] = 'red';
            } else if (in_array($i + 1, $this->blue_ball)) {
                $this->num_attr[($i + 1)]['color'] = 'blue';
            } else if (in_array($i + 1, $this->green_ball)) {
                $this->num_attr[($i + 1)]['color'] = 'green';
            }
        }
    }

    private function getNumberAttr($year, $number)
    {
        $cal = new Calendar();
        $first_zodiac = $cal->Cal($year)['zodiac'];
        $first_zodiac_index = array_search($first_zodiac, $this->zodiacs);
        $index = (60 + $first_zodiac_index - ($number - 1)) % 12;
        $color = '';
        if (in_array($number, $this->blue_ball)) {
            $color = '蓝波';
        } elseif (in_array($number, $this->red_ball)) {
            $color = '红波';
        } elseif (in_array($number, $this->green_ball)) {
            $color = '绿波';
        }
        return ['color' => $color, 'zodiac' => $this->zodiacs[$index]];
    }

    private function isFirstZodiac($year, $zodiac)
    {
        $cal = new Calendar();
        $first_zodiac = $cal->Cal($year)['zodiac'];
        return $zodiac == $first_zodiac;
    }
}