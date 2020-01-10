<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include 'simple_html_dom.php';

class CrawlerController extends Controller
{
    private $html;

    public function index()
    {
        $items = DB::table('crawler_dantri')->get();
        return view('list_crawler', ['items' => $items]);
    }

    private function getDom($link)
    {
        return file_get_html($link);
    }

    public function getUrl()
    {
        $html = $this->getDom('https://dantri.com.vn');
        $cate = $html->find('.nav-wrap .wid1004 .nav a');
        $links = [];
        foreach ($cate as $element) {
            $links[] = "https://dantri.com.vn".$element->href;
        }
        return $links;
    }

    public function getCrawler()
    {
        // Get 1 page category by pagination
        echo "<pre>";
        $urlCategory = $this->getUrl();
        unset($urlCategory[0]);
        $element = $urlCategory[4];
        $html = $this->getDom($element);
        $pagination = $html->find('.container .fl .mt1 .fr', 0);
        $paginationUrl = "https://dantri.com.vn".$pagination->find('a', 0)->href;
        $paginationText = $pagination->plaintext;
        $count = 0;
        while ($paginationText == "[ Trang sau ]" && $count < 100) {
            $count++;
            $htmlCate = $this->getDom($paginationUrl);
            $pagination = $htmlCate->find('.container .fl .mt1 .fr', 0);
            $paginationUrl = "https://dantri.com.vn".$pagination->find('a', 0)->href;

            foreach ($htmlCate->find('.container .fl #listcheckepl .mt3') as $value) {
                $item = [];
                $item['title'] = $value->find('a', 0)->title;
                $item['images'] = $value->find('img',0)->src;
                $item['slug'] = $value->find('a', 0)->href;
                $titleUrl = "https://dantri.com.vn".$value->find('a', 0)->href;
                $htmlTitleUrl = $this->getDom($titleUrl);
                foreach ($htmlTitleUrl->find('.container #ctl00_IDContent_ctl00_divContent') as $element) {
                    $item['date'] = $element->find('span',2)->plaintext;
                    $item['details']= "";
                    foreach ($element->find('#divNewsContent p') as $detail) {
                        $item['details'] .= $detail->plaintext;
                        $this->saveCrawler($item);
                    }

                }
            }
        }
    }

    public function saveCrawler($item)
    {
        try {
            $count = DB::table('crawler_dantri')->where('slug', $item['slug'])->count();
            if ($count > 0) {
                DB::table('crawler_dantri')->where('slug', $item['slug'])
                    ->update($item);
            } else {
                DB::table('crawler_dantri')->insert($item);
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
