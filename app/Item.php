<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Item
 *
 * @property int $id
 * @property int $category_id カテゴリーID
 * @property string|null $asin ASIN
 * @property string|null $isbn ISBN
 * @property string|null $title タイトル
 * @property string|null $author 著者
 * @property string|null $party 関係者
 * @property string|null $publisher ブランド
 * @property string|null $url URL
 * @property string|null $description 詳細
 * @property string|null $review_id レビューID
 * @property string|null $review_content レビュー
 * @property string|null $release_month 発売月
 * @property string|null $release_date 発売日
 * @property string|null $delivery_month 配送予定月
 * @property string|null $delivery_date 配送予定日
 * @property int $type 種類
 * @property int $status ステータス
 * @property int $task_status タスクステータス
 * @property int $request_status リクエストステータス
 * @property int $import_flag 取り込みフラグ
 * @property string|null $note1 その他項目1
 * @property string|null $note2 その他項目2
 * @property string|null $note3 その他項目3
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item ofCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereAsin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeliveryMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereImportFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNote1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNote2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNote3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereParty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereReleaseMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereRequestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereReviewContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereTaskStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUrl($value)
 * @mixin Builder
 */
class Item extends Model {

    protected $guarded = ['id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

}
