<?php

namespace App\Helpers;

use App\Category;
use App\Item;
use Illuminate\Support\Facades\DB;

class RssUtil {

    protected $total_count = 0;
    protected $item_count = 0;

    /**
     * @return int
     */
    public function getTotalCount(): int {
        return $this->total_count;
    }

    /**
     * @param int $total_count
     */
    public function setTotalCount(int $total_count): void {
        $this->total_count = $total_count;
    }

    /**
     * @return int
     */
    public function getItemCount() {
        return $this->item_count;
    }

    /**
     * @param int $item_count
     */
    public function setItemCount($item_count) {
        $this->item_count = $item_count;
    }

    /**
     * TODO:FlinkerのRSSから新刊情報取得
     * @return array
     */
    public function get_book_rss(): array {
        $result        = true;
        $rss_item_list = array();

        $rss_url      = "http://sinkan.net/?action_rss=true&uid=29683&mode=discover&key=b4bc3485497d8719f67ef67385a790d3";
        $ng_word_list = explode(",", "期間限定,無料,試し読み,巻セット,分冊版,月号,2019年,2020年,2021年,volume,comic marginal,単話");

        $category_id = Category::whereCode(__CATEGORY_CODE)->first()->id;

        $rss = simplexml_load_file($rss_url, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($rss !== FALSE) {
            $rss = $rss->channel;

            foreach ($rss->item as $item) {
                $dc = $item->children('http://purl.org/dc/elements/1.1/');

                $book_record_list = array();
                //登録対象タイトルの抽出
                //descriptionに以下が含まれているものは登録不要
                //集英社文庫 (コミック版)
                //雑誌
                if (strpos($item->description, "(集英社文庫 (コミック版))") === FALSE && strpos($item->description, "[雑誌]") === FALSE) {

                    //titleとrelease_dateの取得
                    $title = trim($item->title);
                    preg_match('/([0-9]{4,}-[0-9]{1,2}(-[0-9]{1,2})?)[\s](.*)/', $title, $m);
                    $release_date = "";
                    $title        = "";
                    if (count($m) == 3) {
                        $release_date = $m[1];
                        $title        = $m[2];
                    } else if (count($m) == 4) {
                        $release_date = $m[1];
                        $title        = $m[3];
                    }

                    //ng_word_listに一致するものは登録不要
                    str_replace($ng_word_list, "", $title, $count);
                    if ($count === 0) {
                        //各種情報の抽出
                        $book_properties = array();
                        preg_match('/discover.(\w+)/', $item->guid, $m);
                        $asin                             = isset($m[1]) ? $m[1] : "";
                        $book_properties["asin"]          = str_replace("RK", "", $asin);
                        $book_properties["title"]         = $title;
                        $book_properties["author"]        = mb_substr(trim($dc->creator), 0, 80);
                        $book_properties["release_month"] = $release_date == "" ? "" : date("Y/m", strtotime($release_date));
                        $book_properties["release_date"]  = mb_substr_count($release_date, "-") == 1 ? date("Y/m/1", strtotime($release_date)) : $release_date;
                        $book_properties["description"]   = (string)$item->description;
                        //判定方法見直し
                        if (strpos($book_properties["asin"], "BW") === 0) {
                            $book_properties["item_type"] = __BOOK_ITEM_TYPE_EBOOK;
                        } else {
                            $book_properties["item_type"] = __BOOK_ITEM_TYPE_DEFAULT;
                        }

                        if ($book_properties["asin"]) {
                            $book_record_list[$book_properties["asin"]] = $book_properties;
                        }
                    }
                }

                if (count($book_record_list)) {
                    foreach ($book_record_list as $asin => $book_record) {
                        $rss_item = Item::whereAsin($asin)->first();

                        $item_properties                  = array();
                        $item_properties["category_id"]   = $category_id;
                        $item_properties["asin"]          = $asin;
                        $item_properties["title"]         = $book_record["title"];
                        $item_properties["author"]        = $book_record["author"];
                        $item_properties["description"]   = $book_record["description"];
                        $item_properties["release_month"] = $book_record["release_month"];
                        $item_properties["release_date"]  = $book_record["release_date"];
                        $item_properties["type"]          = $book_record["item_type"];

                        if (isset($rss_item["id"]) && $rss_item["id"]) {
                            //update
                            $item_properties["id"] = $rss_item["id"];
                        } else {
                            $item_properties["task_status"] = __TASK_STATUS_DEFAULT;
                            $item_properties["import_flag"] = __IMPORT_FLAG_UNDONE;
                        }
                        $rss_item_list[] = $item_properties;
                    }
                }

            }

        } else {
            $result = false;
        }

        return array($result, $rss_item_list);
    }

}
