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

    public function index()
    {
//        dump($this->num_attr);
//        $issue = Issue::query()->where('status', '1')->orderBy('date', 'desc')->first();
//        if (!isset($issue)) {
//            $issue = Issue::query()->where('status', '0')->orderBy('date', 'desc')->first();
//        }
        $issues = Issue::query()
//            ->where('status', '2')
//            ->take(20)
            ->where('date', '<', date_create('+12 hour'))
            ->orderBy('id', 'desc')
//            ->simplePaginate();
            ->paginate();
//        dump(4/49 + 4/48 + 4/47 + 4/46 + 4/45 + 4/44 + 4/43);

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

    public function toolsTeNumber(Request $request)
    {
        $issue_count = $request->input('issue_count', 50);//期数
        $issues = Issue::query()
            ->where('status', '2')
            ->take($issue_count)
            ->orderBy('date', 'desc')
            ->get();

        $te_i = [];
        foreach ($issues as $issue) {
            $issue->num7;
        }
        return view('mobiles.tools_trend', ['issue_count' => $issue_count]);
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
