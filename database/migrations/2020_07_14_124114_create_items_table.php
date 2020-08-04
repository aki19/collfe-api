<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->comment('カテゴリーID');
            $table->string('code', 20)->nullable()->comment('コード');
            $table->string('title')->nullable()->comment('タイトル');
            $table->string('author')->nullable()->comment('著者');
            $table->string('party')->nullable()->comment('関係者');
            $table->string('publisher')->nullable()->comment('ブランド');
            $table->string('url')->nullable()->comment('URL');
            $table->text('description')->nullable()->comment('詳細');
            $table->string('review_id', 20)->nullable()->comment('レビューID');
            $table->text('review_content')->nullable()->comment('レビュー');
            $table->string('release_month', 8)->nullable()->comment('発売月');
            $table->date('release_date')->nullable()->comment('発売日');
            $table->string('delivery_month', 8)->nullable()->comment('配送予定月');
            $table->date('delivery_date')->nullable()->comment('配送予定日');
            $table->unsignedTinyInteger('type')->default(0)->comment('種類');
            $table->unsignedTinyInteger('status')->default(0)->comment('ステータス');
            $table->unsignedTinyInteger('task_status')->default(0)->comment('タスクステータス');
            $table->unsignedTinyInteger('request_status')->default(0)->comment('リクエストステータス');
            $table->unsignedTinyInteger('import_flag')->default(0)->comment('取り込みフラグ');
            $table->text('note1')->nullable()->comment('その他項目1');
            $table->text('note2')->nullable()->comment('その他項目2');
            $table->text('note3')->nullable()->comment('その他項目3');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('items');
    }
}
