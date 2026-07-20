<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        // Mengambil semua data negara dari database
        $countries = Country::all();

        // Mengembalikan data dalam bentuk JSON sesuai standar REST API tugas
        return response()->json([
            'status' => 'success',
            'data' => $countries
        ], 200);
    }
}