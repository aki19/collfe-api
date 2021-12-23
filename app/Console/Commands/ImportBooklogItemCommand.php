<?php

namespace App\Console\Commands;

use App\Category;
use App\Helpers\WebAPI;
use App\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use phpQuery;

/**
 * Booklogからスクレイピングした結果を取り込む
 * Class ImportBooklogItemCommand
 * @package App\Console\Commands
 */
class ImportBooklogItemCommand extends Command {

    protected $signature = "import-booklog";

    public function notify($message) {
        Log::info("[" . $this->signature . "] " . $message);
    }

    public function handle() {
        $this->notify("[BEGIN]");

        $book_list = $this->load_element();
        $this->notify("element count " . count($book_list));

        $category_id = Category::whereCode(__CATEGORY_CODE)->first()->id;

        $insert_list = array();
        $update_list = array();
        foreach ($book_list as $id => $book) {
            $item = Item::whereAsin($id)->first();
            if (isset($item["id"]) && $item["id"]) {
                $book["id"]          = $item["id"];
                $book["import_flag"] = __IMPORT_FLAG_DONE;
                $update_list[]       = $book;
            } else {
                $book["category_id"] = $category_id;
                $book["task_status"] = __TASK_STATUS_COMPLETE;
                $book["import_flag"] = __IMPORT_FLAG_DONE;
                $book["created_at"]  = Carbon::now();
                $book["updated_at"]  = Carbon::now();
                $insert_list[]       = $book;
            }
        }

        DB::table('items')->insert($insert_list);

        foreach ($update_list as $book) {
            Item::whereId($book["id"])->update($book);
        }

        $this->notify("[END]");
    }

    private function load_element() {
        $book_list = array();

        $headers   = array();
        $headers[] = "User-Agent: " . "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100";

        list($status_code, $html) = WebAPI::direct_get("https://booklog.jp/timeline/users/aki19?view=1", array(), false, false, $headers);

        if ($status_code == "200") {
            $dom = phpQuery::newDocument($html);

            foreach ($dom->find('#timelineArea > li > div > div  > div > div.b10M') as $tmp) {
                $div = pq($tmp);

                $title_elem = $div->find("h3 > a");
                $title      = $title_elem->text();
                $href       = $title_elem->attr("href");
                preg_match('/\/item\/1\/(\w+)/', $href, $m);
                $book_id = $m[1];

                $info_elem = $div->find("div > a");
                $author    = $info_elem->text();

                $properties           = array();
                $properties["asin"]   = $book_id;
                $properties["title"]  = trim($title);
                $properties["author"] = trim($author);
                $book_list[$book_id]  = $properties;
            }
        }

        return $book_list;
    }

}
