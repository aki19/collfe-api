<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

/**
 * kindleから出力したcsvを取り込む
 * Class ImportKindleItemCommand
 * @package App\Console\Commands
 */
class ImportKindleItemCommand extends Command {

    protected $signature = "import-kindle";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        echo "hello";
        Log::info("hello command");
    }

}
