<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

/**
 * 今日以外のログを消し消しする
 * Class ClearLogFileCommand
 * @package App\Console\Commands
 */
class ClearLogFileCommand extends Command {

    protected $signature = "clear-log-file";

    public function notify($message) {
        Log::info("[" . $this->signature . "] " . $message);
    }

    public function handle() {
        $this->notify("[BEGIN]");

        $today = date("Ymd");

        $path = storage_path("logs") . DIRECTORY_SEPARATOR . "*.log";
        foreach (glob($path) as $val) {
            if (strpos($val, $today) === false) {
                unlink($val);
            }
        }

        $this->notify("[END]");
    }

}
