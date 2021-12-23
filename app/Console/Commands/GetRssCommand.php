<?php

namespace App\Console\Commands;

use App\Helpers\RssUtil;
use App\Http\Controllers\ItemsController;
use App\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

/**
 * TODO:RSS自動取得バッチ
 * Class GetRssCommand
 * @package App\Console\Commands
 */
class GetRssCommand extends Command {

    protected $signature = "get-rss";

    public function notify($message) {
        Log::info("[" . $this->signature . "] " . $message);
    }

    public function handle() {
        $this->notify("[BEGIN]");

        //RSS取得
        $util = new RssUtil();
        list($result, $rss_item_list) = $util->get_book_rss();

        if ($result) {
            $items = new Item();
            foreach ($rss_item_list as $rss_item) {
                if (isset($rss_item["id"]) && $rss_item["id"]) {
                    Item::whereId($rss_item["id"])->update($rss_item);
                } else {
                    $items->create($rss_item);
                }
            }

            $this->notify("[結果]RSS取得処理が正常に完了しました（新規：" . count($rss_item_list) . "件）");
            echo count($rss_item_list) . "件";
        } else {
            $this->notify("[エラー]RSS取得処理に障害が発生したので終了します");
        }

        $this->notify("[END]");
    }

}
