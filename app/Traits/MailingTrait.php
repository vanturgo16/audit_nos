<?php

namespace App\Traits;

use App\Models\MstRules;
use Illuminate\Support\Facades\Http;

trait MailingTrait
{
    public function variableEmail()
    {
        $devRule = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $emailSubmitter = auth()->user()->email ?? '';
        $emailDev = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
        $variableEmail = [
            'devRule' => $devRule,
            'emailSubmitter' => $emailSubmitter,
            'emailDev' => $emailDev,
        ];
        return $variableEmail;
    }
}
