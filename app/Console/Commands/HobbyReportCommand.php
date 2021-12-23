<?php

namespace App\Console\Commands;

use App\Helpers\CSVDownload;
use DateTimeImmutable;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use SplFileObject;

/**
 * 趣味レポートバッチ
 * Class HobbyReportCommand
 * @package App\Console\Commands
 */
class HobbyReportCommand extends Command {

    protected $signature = "hobby-report";

    private $start_datetime;
    private $end_datetime;

    private $booklog_imported_list = array();

    public function notify($message) {
        Log::info("[" . $this->signature . "] " . $message);
    }

    public function handle() {
        $this->notify("[BEGIN]");

        echo date("Y") . "年趣味レポート出力開始" . PHP_EOL;
        echo PHP_EOL;

        //準備
        $this->start_datetime = new DateTimeImmutable(date("Y") . '-01-01 00:00:00');
        $this->end_datetime   = new DateTimeImmutable(date("Y") . '-12-31 23:59:59');

        $this->booklog_imported_list = $this->get_booklog_imported_list();

        $list = array();

        $hobby_list            = array();
        $hobby_list["book"]    = "本";
        $hobby_list["music"]   = "音楽";
        $hobby_list["game"]    = "ゲーム";
        $hobby_list["movie"]   = "映画";
        $hobby_list["travel"]  = "旅行";
        $hobby_list["checkin"] = "外出";
        $hobby_list["figure"]  = "フィギュア";
        foreach ($hobby_list as $key => $val) {
            $item_list = $this->{"collect_" . $key}();
            if (count($item_list)) {
                echo $val . "は " . count($item_list) . " 楽しみました。" . PHP_EOL;
                foreach ($item_list as $item) {
                    $item_properties          = array();
                    $item_properties["title"] = $item["title"];
                    $list[$key][]             = $item_properties;
                }
            } else {
                echo $val . "は楽しみませんでした。" . PHP_EOL;
            }
            echo PHP_EOL;
        }

        //加工可能なようにいったんcsvにして出力する
        $this->output($list);

        echo date("Y") . "年趣味レポート出力終了" . PHP_EOL;

        $this->notify("[END]");
    }

    /**
     * booklogエクスポートファイルより登録日で絞ったリストを返す
     * @return array
     * @throws Exception
     */
    private function get_booklog_imported_list() {
        $booklog_imported_list = array();

        //このコードで隣の項目とくっつく問題が解消された
        setLocale(LC_ALL, 'English_United States.1252');

        //booklog登録済のASINリストを作成
        $buffer = file_get_contents(realpath(__DIR__) . "/files/booklog_export.csv");

        $buffer = mb_convert_encoding($buffer, "UTF-8", "SJIS-Win");

        $handle = tmpfile();
        $meta   = stream_get_meta_data($handle);
        fwrite($handle, $buffer);
        rewind($handle);

        if ($handle) {

            $file = new SplFileObject($meta["uri"], "rb");
            $file->setFlags(SplFileObject::READ_CSV);

            while ($file->valid()) {
                $data = $file->current();

                //skip
                if (!isset($data[0]) || !$data[0]) {
                    $file->next();
                    continue;
                }

                $service_id = trim($data[0]);
                $janle      = trim($data[15]);
                if ($service_id == "1" && in_array($janle, array("本", "電子書籍", "マンガ", "雑誌", "音楽"))) {
                    $create_datetime = new DateTimeImmutable(trim($data[9]));
                    if ($this->start_datetime <= $create_datetime && $create_datetime <= $this->end_datetime) {
                        //値の加工
                        if ($janle == "音楽") {
                            $janle = "music";
                        } else {
                            $janle = "book";
                        }

                        $properties                      = array();
                        $properties["title"]             = trim($data[11]);
                        $properties["status"]            = trim($data[5]);
                        $properties["category"]          = $janle;
                        $properties["subCategory"]       = $data[3] ? trim($data[3]) : "";
                        $properties["linkId"]            = trim($data[1]);
                        $properties["author"]            = $data[12] ? trim($data[12]) : "";
                        $properties["company"]           = $data[13] ? trim($data[13]) : "";
                        $properties["issueYear"]         = $data[14] ? trim($data[14]) : "";
                        $properties["linkCreatedAt"]     = $data[9] ? trim($data[9]) : "";
                        $properties["linkDoneAt"]        = $data[10] ? trim($data[10]) : "";
                        $booklog_imported_list[$janle][] = $properties;
                    }
                }

                $file->next();
            }

            fclose($handle);
        }

        return $booklog_imported_list;
    }

    private function output($list) {
        $hadder_list   = array();
        $hadder_list[] = "趣味カテゴリ";
        $hadder_list[] = "タイトル";

        $download = new CSVDownload;
        $download->set_force_enclosure(true);
        $download->set_output_fields($hadder_list);

        $data_list = array();

        foreach ($list as $key => $items) {
            foreach ($items as $properties) {
                $line   = array();
                $line[] = $key;
                $line[] = $properties["title"];
                //$line[]      = $data["author"];
                $data_list[] = $line;
            }
        }

        $download->set_data_list($data_list);
        fwrite(fopen(realpath(__DIR__) . "/files/hobby_report.csv", "wb"), $download->get_csv());
    }

    /**
     * 本（booklogから取得）
     * @return array
     */
    private function collect_book() {
        $list = array();
        if (isset($this->booklog_imported_list["book"])) {
            foreach ($this->booklog_imported_list["book"] as $item) {
                $item_properties          = array();
                $item_properties["title"] = $item["title"];
                $list[]                   = $item_properties;
            }
        }
        return $list;
    }

    /**
     * 音楽（booklogから取得）
     * @return array
     */
    private function collect_music() {
        $list = array();
        if (isset($this->booklog_imported_list["music"])) {
            foreach ($this->booklog_imported_list["music"] as $item) {
                $item_properties          = array();
                $item_properties["title"] = $item["title"];
                $list[]                   = $item_properties;
            }
        }
        return $list;
    }

    /**
     * TODO:ゲーム（notionから取得）
     * @return array
     */
    private function collect_game() {
        $list = array();
        return $list;
    }

    /**
     * TODO:映画（Filmarksから取得）
     * スクレイピングしか方法がない。微妙・・・。
     * @return array
     */
    private function collect_movie() {
        $list = array();
        return $list;
    }

    /**
     * TODO:旅行（tripitから取得）
     * http://tripit.github.io/api/
     * @return array
     */
    private function collect_travel() {
        $list = array();
        return $list;
    }

    /**
     * TODO:外出（swarmから取得）
     * https://developer.foursquare.com/docs/
     * @return array
     */
    private function collect_checkin() {
        $list = array();
        return $list;
    }

    /**
     * TODO:フィギュア（notionから取得）
     * @return array
     */
    private function collect_figure() {
        $list = array();
        return $list;
    }

}
