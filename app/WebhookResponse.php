<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WebhookResponse
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookResponse query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $type
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookResponse whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookResponse whereType($value)
 */
class WebhookResponse extends Model
{
    protected $table = 'webhook_responses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',
        'text'
    ];

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

}
