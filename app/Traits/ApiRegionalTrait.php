<?php
namespace App\Traits;
use App\Models\MstRules;
use Illuminate\Support\Facades\Http;

trait ApiRegionalTrait
{
    public function getTokenRegional()
    {
        $email = MstRules::where('rule_name', 'Email Auth Regional')->first()->rule_value;        
        $password = MstRules::where('rule_name', 'Password Auth Regional')->first()->rule_value;
        $url = MstRules::where('rule_name', 'API Auth Regional')->first()->rule_value;

        $response = Http::post($url, [
            'email' => decrypt($email),
            'password' => decrypt($password),
        ]);
        $data = $response['data'];
        $token = $data['token'];

        return $token;
    }

    public function getProvinceRegional($token)
    {
        $ruleApiProvince = MstRules::where('rule_name','=','API List Province')->first()->rule_value;

        $getProvince = Http::withToken($token)->get($ruleApiProvince);
        $provinces = $getProvince['data'];

        return $provinces;
    }
}
