<?php

namespace App\Domain\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * id роли менеджер
     */
    const ROLE_MANAGER = 0;

    /**
     * id роли клиент
     */
    const ROLE_CLIENT = 1;

    /**
     * лимит времени в часах
     */
    const LIMIT = 24;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_ticket',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Метод-мутатор хеширования пароля
     * @param string $password
     *
     * @return void
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * геттер возвращает время создания последнего тикета
     *
     * @return string|null
     */
    public function getLastTicket(): ?string
    {
        return $this->last_ticket;
    }

    /**
     * проверка можно ли создать тикет
     *
     * @return bool
     */
    public function isSendingPossible(): bool
    {
        if(!$this->getLastTicket()) {
            return true;
        }

        $hours = Carbon::now()->diffInHours($this->getLastTicket());

        return $hours >= self::LIMIT;
    }
}
