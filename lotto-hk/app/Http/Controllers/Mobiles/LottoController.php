<?php

namespace App\Http\Controllers\Mobiles;

use App\Http\Controllers\Spider\Calendar;
use App\Models\Column;
use App\Models\Issue;
use App\Models\NewspaperIssue;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class LottoController extends BaseController
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

    public function __construct()
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
        if ($year == date('Y')) {
            $first_zodiac_index = $this->first_zodiac_index;
        } else {
            $first_zodiac = $cal->Cal($year)['zodiac'];
            $first_zodiac_index = array_search($first_zodiac, $this->zodiacs);
        }
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

    public function index()
    {
        $issues = Issue::query()
            ->where('date', '<', date_create('+12 hour'))
            ->orderBy('id', 'desc')
            ->paginate();
        return view('mobiles.index', ['issues' => $issues, 'num_attr' => $this->num_attr]);
    }

    public function newspapers(Request $request)
    {
        $issues = Issue::query()
//            ->where('date', '<', date_create('+24 hour'))
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $nis = [];
        $curr_issue = '';
        if ($request->has('issue')) {
            $curr_issue = $request->input('issue');
            $nis = NewspaperIssue::query()->where('issue', $request->input('issue'))->get();
        }
        if (empty($nis)) {
            foreach ($issues as $issue) {
                $nis = NewspaperIssue::query()->where('issue', $issue->id)->get();
                if (!empty($nis)) {
                    $curr_issue = $issue->id;
                    break;
                }
            }
        }
        return view('mobiles.newspapers', ['issues' => $issues, 'nis' => $nis, 'curr_issue' => $curr_issue]);
    }

    public function tools()
    {
        return view('mobiles.tools', [
            'zodiacs_ball' => $this->zodiacs_ball,
            'jin_ball' => $this->jin_ball, 'mu_ball' => $this->mu_ball, 'shui_ball' => $this->shui_ball, 'huo_ball' => $this->huo_ball, 'tu_ball' => $this->tu_ball,
            'red_ball' => $this->red_ball, 'green_ball' => $this->green_ball, 'blue_ball' => $this->blue_ball,
            'num_attr' => $this->num_attr]);
    }

    //分析
    public function toolsAnalyses(Request $request, $action)
    {
        $issue_count = $request->input('issue_count', 50);//期数
        $issues = Issue::query()
            ->where('status', '2')
            ->take($issue_count)
            ->orderBy('date', 'desc')
            ->get();
        $te_numbers = [];
        $te_zodiacs = [];
        $te_weishus = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $te_colors = ['红波' => 0, '蓝波' => 0, '绿波' => 0];
        $ping_numbers = [];
        $ping_zodiacs = [];
        $ping_weishus = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $ping_colors = ['红波' => 0, '蓝波' => 0, '绿波' => 0];
        for ($i = 1; $i <= 49; $i++) {
            $te_numbers[($i < 10 ? '0' : '') . $i] = 0;
            $ping_numbers[($i < 10 ? '0' : '') . $i] = 0;
        }
        foreach ($this->zodiacs as $zodiac) {
            $te_zodiacs[$zodiac] = 0;
            $ping_zodiacs[$zodiac] = 0;
        }
        foreach ($issues as $issue) {
            $num7Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num7);
            $te_numbers[$issue->num7] += 1;
            $te_weishus[$issue->num7 % 10] += 1;
            $te_zodiacs[$num7Attr['zodiac']] += 1;
            $te_colors[$num7Attr['color']] += 1;

            $num1Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num1);
            $ping_numbers[$issue->num1] += 1;
            $ping_weishus[$issue->num1 % 10] += 1;
            $ping_zodiacs[$num1Attr['zodiac']] += 1;
            $ping_colors[$num1Attr['color']] += 1;

            $num2Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num2);
            $ping_numbers[$issue->num2] += 1;
            $ping_weishus[$issue->num2 % 10] += 1;
            $ping_zodiacs[$num2Attr['zodiac']] += 1;
            $ping_colors[$num2Attr['color']] += 1;

            $num3Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num3);
            $ping_numbers[$issue->num3] += 1;
            $ping_weishus[$issue->num3 % 10] += 1;
            $ping_zodiacs[$num3Attr['zodiac']] += 1;
            $ping_colors[$num3Attr['color']] += 1;

            $num4Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num4);
            $ping_numbers[$issue->num4] += 1;
            $ping_weishus[$issue->num4 % 10] += 1;
            $ping_zodiacs[$num4Attr['zodiac']] += 1;
            $ping_colors[$num4Attr['color']] += 1;

            $num5Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num5);
            $ping_numbers[$issue->num5] += 1;
            $ping_weishus[$issue->num5 % 10] += 1;
            $ping_zodiacs[$num5Attr['zodiac']] += 1;
            $ping_colors[$num5Attr['color']] += 1;

            $num6Attr = $this->getNumberAttr(substr($issue->date, 0, 4), $issue->num6);
            $ping_numbers[$issue->num6] += 1;
            $ping_weishus[$issue->num6 % 10] += 1;
            $ping_zodiacs[$num6Attr['zodiac']] += 1;
            $ping_colors[$num6Attr['color']] += 1;
        }
        arsort($te_numbers);
        arsort($te_weishus);
        arsort($te_zodiacs);
        arsort($te_colors);
        arsort($ping_numbers);
        arsort($ping_weishus);
        arsort($ping_zodiacs);
        arsort($ping_colors);
        return view('mobiles.tools_trend', [
            'issue_count' => $issue_count,
            'action' => $action,
            'te_numbers' => $te_numbers,
            'te_weishus' => $te_weishus,
            'te_zodiacs' => $te_zodiacs,
            'te_colors' => $te_colors,
            'ping_numbers' => $ping_numbers,
            'ping_weishus' => $ping_weishus,
            'ping_zodiacs' => $ping_zodiacs,
            'ping_colors' => $ping_colors]);
    }

    public function columns()
    {
        $columns = Column::query()->where('id', 6)->orWhere('id', 7)->get();
        return view('mobiles.columns', ['columns' => $columns]);
    }

    public function tz_te()
    {
        $issue = Issue::query()->where('status', '0')->orderBy('date', 'desc')->first();
        return view('mobiles.tz_te', ['issue' => $issue, 'num_attr' => $this->num_attr, 'zodiacs_ball' => $this->zodiacs_ball]);
    }

    public function tz_ping()
    {
        return view('mobiles.tz_ping');
    }
}
