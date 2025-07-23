<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'borrower_id', 'status'];

    // 🔁 العلاقة مع الكتاب
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // 🔁 العلاقة مع المستعير
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
