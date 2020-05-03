<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TelegramUser
 *
 * @property int $id
 * @property int|null $is_bot
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property string $language_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereIsBot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereUsername($value)
 * @mixin \Eloquent
 */
class TelegramUser extends Model
{
    protected $guarded = [];
    protected $table = 'telegram_users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'language_code',
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
     * @return int|null
     */
    public function getIsBot(): ?int
    {
        return $this->is_bot;
    }

    /**
     * @param int|null $is_bot
     */
    public function setIsBot(?int $is_bot): void
    {
        $this->is_bot = $is_bot;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string|null $first_name
     */
    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string|null $last_name
     */
    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getLanguageCode(): string
    {
        return $this->language_code;
    }

    /**
     * @param string $language_code
     */
    public function setLanguageCode(string $language_code): void
    {
        $this->language_code = $language_code;
    }


}
