<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/upload",
     *   tags={"Dokumentasi"},
     *   summary="Upload dokumentasi file",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"file"},
     *         @OA\Property(property="file", type="string", format="binary")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="File uploaded"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/dokumentasi');
            $url = Storage::url($path);

            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => $url
            ], 200);
        }

        return response()->json(['message' => 'File upload failed'], 400);
    }
}