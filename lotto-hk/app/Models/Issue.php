<?php

namespace App\Models;

class Issue extends LModel
{
    const k_status_unknown = 0;
    const k_status_doing = 1;
    const k_status_done = 2;

    static public function currentIssue()
    {
        $issue = Issue::query()->where('status', self::k_status_unknown)->orderBy('date', 'asc')->first();
        if (!isset($issue)) {
            $issue = Issue::query()->where('status', self::k_status_doing)->orderBy('date', 'desc')->first();
        }
        if (!isset($issue)) {
            $issue = Issue::query()->where('status', self::k_status_done)->orderBy('date', 'desc')->first();
        }
        return $issue;
    }
}
