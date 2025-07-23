<?php

namespace App\Http\Controllers; 

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;

class BookController extends Controller
{
    // عرض جميع الكتب
    public function index()
    {
        return Book::with('user')->get();
    }

    // إضافة كتاب جديد
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'nullable|string',
            'available' => 'required|boolean',
        ]);

        // تحقق من أن المستخدم معير (lender)
        $user = User::find($request->user_id);
        if ($user->user_type !== 'lender') {
            return response()->json(['error' => 'فقط المعير يمكنه إضافة الكتب'], 403);
        }
               
               Log::info('تم استقبال الطلب بنجاح', $request->all());

        $book = Book::create($request->all());
        return response()->json($book, 201);
    }

    // عرض تفاصيل كتاب
    public function show($id)
    {
        $book = Book::with('user')->findOrFail($id);
        return response()->json($book);
    }

    // تعديل كتاب
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return response()->json($book);
    }

    // حذف كتاب
    public function destroy($id)
    {
        Book::findOrFail($id)->delete();
        return response()->json(['message' => 'تم الحذف']);
    }
}
