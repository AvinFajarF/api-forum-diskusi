<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /*
    TODO:
        Function untuk register
        Function untuk login
    */
    public function register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
            "email" => "required",
        ]);

        if ($validasi->fails()) {
            // jika gagak dari validasi
            return response()->json([
                "status" => "Error",
                "message" => "Gagal ketika registasi",
                "token" => null
            ], 400);
        }

        // mengecek email sudah ada di database atau belum
        $checkEmail = User::where("email", $request->email)->first();
        if ($checkEmail) {
            return response()->json([
                "status" => "Error",
                "message" => "Error email terdaftar",
                "token" => null
            ], 400);
        }

        // jika lolos dari validasi

        $validatedData = $validasi->validated();
        $res = User::create([
            "username" => $validatedData["username"],
            "email" => $validatedData["email"],
            "password" => $validatedData["password"],
        ]);

        if ($res) {
            $token = $res->createToken($validatedData["email"]);
            return response()->json([
                "status" => "Success",
                "message" => "Success registrasi",
                "token" => $token->plainTextToken
            ], 201);
        }


        // jika user gagal di buat
        return response()->json([
            "status" => "Error",
            "message" => "Gagal ketika registasi",
            "token" => null
        ], 400);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // mengecek email dari request user ke database
        $user = User::where('email', $request->email)->first();

        // check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => "Error",
                "message" => "Kredensial yang diberikan salah",
                "token" => null
            ], 401);
        }

        // membuat token
        $token = $user->createToken($request->email);

        return response()->json([
            "status" => "Success",
            "message" => "Success Login",
            "token" => $token->plainTextToken
        ], 200);
    }
}
