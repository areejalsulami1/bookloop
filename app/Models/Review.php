<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // المراجع
        'book_id',   // الكتاب الذي تمت مراجعته
        'rating',    // التقييم (مثلاً: من 1 إلى 5)
        'comment',   // التعليق النصي
    ];

    // ==========================
    //  العلاقات Relationships
    // ==========================

    // المراجعة مرتبطة بمستخدم واحد (اللي كتبها)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // والمراجعة مرتبطة بكتاب واحد
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
