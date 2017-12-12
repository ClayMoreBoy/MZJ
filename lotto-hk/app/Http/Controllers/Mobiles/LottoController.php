<?php

namespace App\Http\Controllers\Mobiles;

use App\Models\Column;
use App\Models\Issue;
use App\Models\NewspaperIssue;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class LottoController extends BaseController
{
    use LBallParse;

    public function __construct()
    {
        $this->initBalls();
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
}
