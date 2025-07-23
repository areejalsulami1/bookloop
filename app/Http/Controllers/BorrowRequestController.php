<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;

class BorrowRequestController extends Controller
{
    // 🟢 عرض جميع طلبات الاستعارة (GET)
    public function index()
    {
        $requests = BorrowRequest::with('book')->get();
        return response()->json($requests);
    }

    // 🟢 إنشاء طلب استعارة جديد (POST)
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_id' => 'required|exists:users,id',
        ]);

        $borrowRequest = BorrowRequest::create([
            'book_id' => $request->book_id,
            'borrower_id' => $request->borrower_id,
        ]);

        return response()->json(['message' => 'تم إرسال الطلب بنجاح', 'request' => $borrowRequest]);
    }
}
