<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        // المستخدم الذي تصله الإشعارات
        'message',        // محتوى الإشعار
        'is_read',        // هل تم قراءة الإشعار؟
    ];

    // العلاقة: كل إشعار ينتمي إلى مستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
