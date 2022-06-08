<?php

namespace App\Models;

use App\Models\UserConnection;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    /* Relationships */
    public function suggestions() {
        $userId = auth()->id();
        return $this->hasMany(UserConnection::class, 'receiver_id')->where('receiver_id', $userId);
    }

    private function skipUsers() {
        $userId = auth()->id();
        $userWhoSendRequests = UserConnection::select('receiver_id')->where(array(
            'sender_id' => $userId,
        ))->whereIn('status', [0, 1])->get()->pluck('receiver_id')->toArray();

        $userWhoReceiveRequests = UserConnection::select('sender_id')->where(array(
            'receiver_id' => $userId,
        ))->whereIn('status', array(0, 1))->get()->pluck('sender_id')->toArray();

        return array_unique(array_merge($userWhoSendRequests, $userWhoReceiveRequests));
    }

    /* Scopes */
    public function scopeGetSuggestions( $query, $offset = 0, $withCount = false ) {
        $skipUsers = $this->skipUsers();
        $query->select(array(
            'id', 'name', 'email',
        ))->where(array(
            array('id', '!=', auth()->id()),
        ))->whereNotIn('id', $skipUsers)->inRandomOrder();

        if ($withCount) {
            return $query->count();
        }
        return $query->limit($offset)->take(10)->get();
    }
}
