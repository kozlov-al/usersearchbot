<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * App\Basket
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Product $baskets
 * @property-read \App\TelegramUser $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basket whereUserId($value)
 * @mixin \Eloquent
 */
class Basket extends Model
{
    protected $table = 'baskets';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * @return BelongsTo
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(TelegramUser::class);
    }

    /**
     * @return BelongsTo
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     */
    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }


}
