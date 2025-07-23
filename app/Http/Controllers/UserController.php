<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth;

class UserController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    // ✅ تسجيل مستخدم جديد
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'user_type' => 'required|in:lender,borrower,both',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role_id = Role::where('name', $request->user_type)->value('id');
        if (!$role_id) {
            return response()->json(['error' => 'Role not found'], 500);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'user_type' => $request->user_type,
            'role_id'   => $role_id,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    // ✅ تسجيل الدخول
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = base64_encode($user->id . '|' . now());

        return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user]);
    }

    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->update($request->only(['name', 'email', 'user_type']));
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function search($query)
    {
        $q = '%' . $query . '%';
        $users = User::where('name', 'like', $q)->orWhere('email', 'like', $q)->get();
        return response()->json($users);
    }

    public function filterByType($type)
    {
        $validTypes = ['lender', 'borrower', 'both'];
        if (!in_array($type, $validTypes)) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $users = User::where('user_type', $type)->get();
        return response()->json($users);
    }
}
