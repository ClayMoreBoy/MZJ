<?php
/**
 * Created by PhpStorm.
 * User: maozhijun
 * Date: 17/11/30
 * Time: 15:12
 */

namespace App\Http\Controllers\Mobiles;

use App\Models\UMerchantAccount;
use Illuminate\Http\Request;

trait LDomainParse
{
    public function parse(Request $request)
    {
        $result = [];
        $domain = parse_url($request->fullUrl(), PHP_URL_HOST);
        $strs = explode('.', $domain);
        if (isset($strs[0])) {
            $agent_domain = $strs[0];
        }
        if (isset($strs[1])) {
            $merchant_domain = $strs[1];
        }
        if (isset($strs[2])) {
            $name = $strs[2];
        }
        if (isset($strs[3])) {
            $com = $strs[3];
        }
        if (isset($com)) {
            $result['domain'] = $name . '.' . $com;
            $uma = UMerchantAccount::query()->where('domain', $merchant_domain)->first();
            if (isset($uma)) {
                $result['merchant'] = $uma;
                $uaa = $uma->agents()->where('domain', $agent_domain)->first();
                if ($uaa) {
                    $result['agent'] = $uaa;
                }
            }
        } elseif (isset($name)) {
            $result['domain'] = $merchant_domain . '.' . $name;
            $uma = UMerchantAccount::query()->where('domain', $agent_domain)->first();
            if (isset($uma)) {
                $result['merchant'] = $uma;
            }
        } else {
            $result['domain'] = $agent_domain . '.' . $merchant_domain;
        }
        return $result;
    }
}