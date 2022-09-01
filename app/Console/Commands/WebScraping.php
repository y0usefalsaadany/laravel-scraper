<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;

class WebScraping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webScraping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command to make scraping';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = [];
        $website = 'https://eg.opensooq.com/ar/%D8%B3%D9%8A%D8%A7%D8%B1%D8%A7%D8%AA-%D9%88%D9%85%D8%B1%D9%83%D8%A8%D8%A7%D8%AA/all';
        $client = new Client();
        $crawler = $client->request('GET',$website);
        $crawler->filter('#gridPostListing li')->each( function($element) use ($data){
            $title = $element->filter('div h2')->text();
            $img = $element->filter('.overflowHidden img')->attr('src');
            $price = $element->filter('div .price-wrapper .inline');
            $scrapingData= [];
            $scrapingData['title'] = $title;
            $scrapingData['img'] = $img;
            $scrapingData['price'] = $price;
            array_push($data,$scrapingData);
            // dd($title);
        });
        Storage::disk('public')->put('data.json',json_encode($data, JSON_UNESCAPED_UNICODE));
        // dd($crawler);
        return 0;
    }
}
