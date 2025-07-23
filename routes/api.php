<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRequestController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| هنا نحدد جميع الـ API التي نستخدمها في المشروع
*/

Route::middleware('api')->group(function () {

    // ✅ تسجيل مستخدم جديد
    Route::post('/register', [UserController::class, 'register']);

    // ✅ تسجيل الدخول (Firebase JWT)
    Route::post('/login', [UserController::class, 'login']);

    // ✅ جلب كل المستخدمين
    Route::get('/users', [UserController::class, 'index']);

    // ✅ جلب مستخدم معيّن
    Route::get('/users/{id}', [UserController::class, 'show']);

    // ✅ تحديث بيانات مستخدم
    Route::put('/users/{id}', [UserController::class, 'update']);

    // ✅ حذف مستخدم
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // ✅ البحث باسم أو إيميل
    Route::get('/users/search/{query}', [UserController::class, 'search']);

    // ✅ الفلترة حسب النوع (معير/مستعير)
       Route::get('/users/type/{type}', [UserController::class, 'filterByType']);

    // ✅ جلب جميع الكتب
    Route::get('/books', [BookController::class, 'index']);

    // ✅ إنشاء كتاب (معير فقط)
       Route::post('/books', [BookController::class, 'store']);

    // ✅ عرض تفاصيل كتاب معيّن
    Route::get('/books/{id}', [BookController::class, 'show']);

    // ✅ تعديل كتاب
     Route::put('/books/{id}', [BookController::class, 'update']);

    // ✅ حذف كتاب
     Route::delete('/books/{id}', [BookController::class, 'destroy']);
       
         Route::get('/borrow-requests', [BorrowRequestController::class, 'index']); // ✅ عرض الطلبات
     Route::post('/borrow-requests', [BorrowRequestController::class, 'store']); // ✅ إنشاء طلب استعارة
     
      });

