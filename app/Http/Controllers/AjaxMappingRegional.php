<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\MstRules;
use App\Traits\ApiRegionalTrait;

class AjaxMappingRegional extends Controller
{
    use ApiRegionalTrait;

    public function selectCity($province_id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List City
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API List City')->first()->rule_value;
        $data = json_encode(['province_id' => $province_id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $city=$response->data;

        return json_encode($city);
    }

    public function selectDistrict($city_id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List District
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API List District')->first()->rule_value;
        $data = json_encode(['city_id' => $city_id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $district=$response->data;

        return json_encode($district);
    }

    public function selectSubDistrict($district_id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List District
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API List Sub District')->first()->rule_value;
        $data = json_encode(['district_id' => $district_id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $subdistrict=$response->data;

        return json_encode($subdistrict);
    }

    public function selectPostalCode($subdistrict_id)
    {
        $tokenregional = $this->getTokenRegional();

        // Get List District
        $clientListCity = new Client();
        $url=MstRules::where('rule_name','API Search Sub District by ID')->first()->rule_value;
        $data = json_encode(['subdistrict_id' => $subdistrict_id]);
        $request = $clientListCity->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenregional,
                'Content-Type' => 'application/json', 
                'Accept' => 'application/json'
            ],
            'body' => $data        
        ]);
        $response = json_decode($request->getBody());
        $postalcode=$response->data;

        return json_encode($postalcode);
    }

}
