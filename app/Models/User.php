<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'user_type'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class, 'borrower_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}

