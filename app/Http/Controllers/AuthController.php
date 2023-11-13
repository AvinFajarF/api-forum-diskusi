<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if ($checkEmail){
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
            $token = $res->createToken($validatedData["username"]);
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
}
