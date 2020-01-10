<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include_once 'simple_html_dom.php';

class CrawlerGoogleplayController extends Controller
{
    public function Get_Data_API($page,$size)
    {
        $url = 'http://api.tovicorp.com/listAppImage?page='.$page.'&size='.$size;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data  = json_decode($response);
        $value = json_decode(json_encode($data), true);
        return $value;
    }

    public function Crawler()
    {
        $index = 0;
        for ($i = 1;$i <= 3156;$i++) {
            $data = $this->Get_Data_API($i, 10);
            $apps = $data['data'];
            if(empty($apps))
                continue;
            var_dump($i);
            foreach ($apps as $app) {

                $url = 'https://play.google.com/store/apps/details?id=' . $app['appid'];
                try {
                    $html = file_get_html($url);
                    var_dump('ok');
                } catch (\Exception $e) {
                    continue;
                }

                $title = utf8_encode($html->find('.AHFaub span', 0)->plaintext);
                $image = $html->find('.xSyT2c img', 0)->src;

                DB::table('crawler_googleplay')->insert([
                    'app_id' => $app['appid'],
                    'app_title' => $title,
                    'app_image' => $image,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                ]);

                var_dump($app['appid'] . ' - success - ' . $index);
                $index++;
            }
        }
        var_dump('All complete');
    }

    public function craw()
    {
        $apps = DB::table('google_play')->get();
        foreach ($apps as $app)
        {
            echo '<img src="'.$app->app_image.'" alt="" style="width:50px;height:50px"> <br>';
        }
    }
}
