<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Users;
use Exception;

class UsersController extends Controller
{
    function feltolt($name, $username)
    {
        $users = new Users();
        $users->name = $name;
        $users->username = $username;
        $users->save();
        return response()->json([
            'status' => 200,
            'uzenet' => 'sikeres feltoltes'
        ],200);
    }
    function delete($id)
    {
        Users::find($id)->delete();
        return response()->json([
            'status' => 200,
            'uzenet' => 'sikeres torles'
        ],200);
    }

    function betolt(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->input('id');
            $adatok = Users::find($id);
            return response()->json($adatok);
        }
        else
        {
            $adatok = Users::all();
            return response()->json($adatok);
        }
    }
}