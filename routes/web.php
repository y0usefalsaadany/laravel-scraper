<?php

use Illuminate\Support\Facades\Route;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
        $data = array();
        $website = 'https://eg.opensooq.com/ar/%D8%B3%D9%8A%D8%A7%D8%B1%D8%A7%D8%AA-%D9%88%D9%85%D8%B1%D9%83%D8%A8%D8%A7%D8%AA/all';
        $client = new Client();
        $crawler = $client->request('GET',$website);
        $crawler->filter('#gridPostListing li')->each( function($element) use (&$data){
            $scrapingData= [];
            $element->filter('div h2')->each( function($element) use (&$data,&$scrapingData){
                $scrapingData['title'] = $element->text();
            });
            $element->filter('.overflowHidden img')->each( function($element) use (&$data,&$scrapingData){
                $scrapingData['img'] = $element->attr('src');
            });
            $element->filter('div .price-wrapper .inline')->each( function($element) use (&$data,&$scrapingData){
                $scrapingData['price'] = $element->text();
            });

            array_push($data,$scrapingData);
        });
        Storage::disk('public')->put('data.json',json_encode($data, JSON_UNESCAPED_UNICODE));
        return response()->json($data);
});
