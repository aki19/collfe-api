<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use SplFileObject;

/**
 * kindleから出力したcsvを取り込む（いらないかも）
 * Class ImportKindleItemCommand
 * @package App\Console\Commands
 */
class ImportKindleItemCommand extends Command {

    protected $signature = "import-kindle";

    public function notify($message) {
        Log::info("[" . $this->signature . "] " . $message);
    }

    public function handle() {
        $this->notify("[BEGIN]");

        $book_list = $this->load_file();
        $this->notify("element count " . count($book_list));
    }

    private function load_file() {
        $book_list = array();

        $buffer = file_get_contents(realpath(__DIR__) . "/files/Kindle.csv");

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

                $properties           = array();
                $properties["asin"]   = trim($data[0]);
                $properties["title"]  = trim($data[1]);
                $properties["author"] = trim($data[2]);
                $book_list[]          = $properties;

                $file->next();
            }

            fclose($handle);
        }

        return $book_list;
    }

}
