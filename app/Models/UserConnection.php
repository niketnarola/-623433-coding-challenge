<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConnection extends Model
{
    use HasFactory;

    protected $mode = '';

    protected $fillable = array(
        'sender_id', 'receiver_id', 'status', 'request_sent_at',
    );

    /* scopes */
    public function scopeIsConnected( $query, $userId ) {
        return $query->where(array(
            'sender_id' => $userId,
            'status' => 0,
        ));
    }

    public function scopeNotReceiverUser( $query, $userId ) {
        return $query->where(array(
            'receiver_id' => $userId,
        ));
    }

    public function scopeNotSenderUser( $query, $userId ) {
        return $query->where(array(
            'sender_id' => $userId,
        ));
    }

    public function scopeGetRequests( $query, $field, $status = 0, $offset = 0, $withCount = false ) {
        $userId = auth()->id();

        $query->where(array(
            'status' => $status,
            $field => $userId,
        ));

        if ($withCount) {
            return $query->count();
        }
        return $query->limit($offset)->take(10)->get();
    }

    /* Relationships */
    public function users() {
        return $this->belongsToMany(User::class, 'user_connections', 'receiver_id', 'sender_id');
    }

    public function sender_user() {
        return $this->belongsTo(User::class, 'sender_id', 'id')->select(array(
            'id', 'name', 'email'
        ));
    }

    public function receiver_user() {
        return $this->belongsTo(User::class, 'receiver_id', 'id')->select(array(
            'id', 'name', 'email'
        ));
    }

    public function user() {
        return $this->hasOne(User::class);
    }
}
