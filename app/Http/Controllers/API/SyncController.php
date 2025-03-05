<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SyncController extends Controller
{
    public function sync(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string',
            'photo'   => 'required|file|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Simpan foto ke storage/app/public/photos
        $path = $request->file('photo')->store('public/photos');
        $photoUrl = Storage::url($path);

        // Simpan ke database (jika diperlukan)
        $data = [
            'barcode'  => $request->barcode,
            'photoUrl' => $photoUrl,
        ];
        // Contoh simpan ke database jika punya model
        // $sync = Sync::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data synchronized successfully',
            'data'    => $data,
        ], 200);
    }
}
