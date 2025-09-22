<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\Implementasi;
use App\Models\Monitoring;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * =========================
     *   SCHEMA DEFINITIONS
     * =========================
     */

    /**
     * @OA\Schema(
     *   schema="Perencanaan",
     *   type="object",
     *   required={"nama_perusahaan","nama_pic","narahubung","jenis_kegiatan","lokasi","jumlah_bibit","jenis_bibit","tanggal_pelaksanaan","lat","long"},
     *   @OA\Property(property="nama_perusahaan", type="string"),
     *   @OA\Property(property="nama_pic", type="string"),
     *   @OA\Property(property="narahubung", type="string"),
     *   @OA\Property(property="jenis_kegiatan", type="string", enum={"Planting Mangrove","Coral Transplanting"}),
     *   @OA\Property(property="lokasi", type="string"),
     *   @OA\Property(property="jumlah_bibit", type="integer"),
     *   @OA\Property(property="jenis_bibit", type="string"),
     *   @OA\Property(property="tanggal_pelaksanaan", type="string", format="date"),
     *   @OA\Property(property="lat", type="string"),
     *   @OA\Property(property="long", type="string"),
     * )
     *
     * @OA\Schema(
     *   schema="Implementasi",
     *   type="object",
     *   required={"nama_perusahaan_sesuai","lokasi_sesuai","jenis_kegiatan_sesuai","jumlah_bibit_sesuai","jenis_bibit_sesuai","tanggal_sesuai","pic_koorlap","lat","long"},
     *   @OA\Property(property="nama_perusahaan_sesuai", type="boolean"),
     *   @OA\Property(property="lokasi_sesuai", type="boolean"),
     *   @OA\Property(property="jenis_kegiatan_sesuai", type="boolean"),
     *   @OA\Property(property="jumlah_bibit_sesuai", type="boolean"),
     *   @OA\Property(property="jenis_bibit_sesuai", type="boolean"),
     *   @OA\Property(property="tanggal_sesuai", type="boolean"),
     *   @OA\Property(property="pic_koorlap", type="string"),
     *   @OA\Property(property="dokumentasi_kegiatan", type="string", nullable=true),
     *   @OA\Property(property="geotagging", type="string", nullable=true),
     *   @OA\Property(property="lat", type="string"),
     *   @OA\Property(property="long", type="string"),
     * )
     *
     * @OA\Schema(
     *   schema="Monitoring",
     *   type="object",
     *   required={"jumlah_bibit_ditanam","jumlah_bibit_mati","diameter_batang","jumlah_daun","daun_mengering","daun_layu","daun_menguning","bercak_daun","daun_serangga","survival_rate"},
     *   @OA\Property(property="jumlah_bibit_ditanam", type="integer"),
     *   @OA\Property(property="jumlah_bibit_mati", type="integer"),
     *   @OA\Property(property="diameter_batang", type="number"),
     *   @OA\Property(property="jumlah_daun", type="integer"),
     *   @OA\Property(property="daun_mengering", type="string", enum={"<25%","25–45%","50–74%",">75%"}),
     *   @OA\Property(property="daun_layu", type="string", enum={"<25%","25–45%","50–74%",">75%"}),
     *   @OA\Property(property="daun_menguning", type="string", enum={"<25%","25–45%","50–74%",">75%"}),
     *   @OA\Property(property="bercak_daun", type="string", enum={"<25%","25–45%","50–74%",">75%"}),
     *   @OA\Property(property="daun_serangga", type="string", enum={"<25%","25–45%","50–74%",">75%"}),
     *   @OA\Property(property="survival_rate", type="number", format="float"),
     *   @OA\Property(property="dokumentasi_monitoring", type="string", nullable=true),
     * )
     */

    /**
     * @OA\Post(
     *   path="/api/perencanaan",
     *   tags={"Perencanaan"},
     *   summary="Create perencanaan",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Perencanaan")
     *   ),
     *   @OA\Response(response=201, description="Perencanaan created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function createPerencanaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string',
            'nama_pic' => 'required|string',
            'narahubung' => 'required|string',
            'jenis_kegiatan' => 'required|in:Planting Mangrove,Coral Transplanting',
            'lokasi' => 'required|string',
            'jumlah_bibit' => 'required|integer',
            'jenis_bibit' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $perencanaan = Perencanaan::create([
            'user_id' => auth()->id(),
            'nama_perusahaan' => $request->nama_perusahaan,
            'nama_pic' => $request->nama_pic,
            'narahubung' => $request->narahubung,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'lokasi' => $request->lokasi,
            'jumlah_bibit' => $request->jumlah_bibit,
            'jenis_bibit' => $request->jenis_bibit,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return response()->json([
            'message' => 'Perencanaan created successfully',
            'perencanaan' => $perencanaan
        ], 201);
    }

    /**
     * @OA\Get(
     *   path="/api/perencanaan/{id}",
     *   tags={"Perencanaan"},
     *   summary="Get perencanaan by ID",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function getPerencanaan($id)
    {
        $perencanaan = Perencanaan::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($perencanaan);
    }

    /**
     * @OA\Post(
     *   path="/api/implementasi/{perencanaan_id}",
     *   tags={"Implementasi"},
     *   summary="Create implementasi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="perencanaan_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Implementasi")
     *   ),
     *   @OA\Response(response=201, description="Implementasi created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function createImplementasi(Request $request, $perencanaan_id)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan_sesuai' => 'required|boolean',
            'lokasi_sesuai' => 'required|boolean',
            'jenis_kegiatan_sesuai' => 'required|boolean',
            'jumlah_bibit_sesuai' => 'required|boolean',
            'jenis_bibit_sesuai' => 'required|boolean',
            'tanggal_sesuai' => 'required|boolean',
            'pic_koorlap' => 'required|string',
            'dokumentasi_kegiatan' => 'nullable|string',
            'geotagging' => 'nullable|string',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $implementasi = Implementasi::create([
            'perencanaan_id' => $perencanaan_id,
            'nama_perusahaan_sesuai' => $request->nama_perusahaan_sesuai,
            'lokasi_sesuai' => $request->lokasi_sesuai,
            'jenis_kegiatan_sesuai' => $request->jenis_kegiatan_sesuai,
            'jumlah_bibit_sesuai' => $request->jumlah_bibit_sesuai,
            'jenis_bibit_sesuai' => $request->jenis_bibit_sesuai,
            'tanggal_sesuai' => $request->tanggal_sesuai,
            'pic_koorlap' => $request->pic_koorlap,
            'dokumentasi_kegiatan' => $request->dokumentasi_kegiatan,
            'geotagging' => $request->geotagging,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return response()->json([
            'message' => 'Implementasi created successfully',
            'implementasi' => $implementasi
        ], 201);
    }

    /**
     * @OA\Post(
     *   path="/api/monitoring/{implementasi_id}",
     *   tags={"Monitoring"},
     *   summary="Create monitoring",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="implementasi_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Monitoring")
     *   ),
     *   @OA\Response(response=201, description="Monitoring created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function createMonitoring(Request $request, $implementasi_id)
    {
        $validator = Validator::make($request->all(), [
            'jumlah_bibit_ditanam' => 'required|integer',
            'jumlah_bibit_mati' => 'required|integer',
            'diameter_batang' => 'required|numeric',
            'jumlah_daun' => 'required|integer',
            'daun_mengering' => 'required|in:<25%,25–45%,50–74%,>75%',
            'daun_layu' => 'required|in:<25%,25–45%,50–74%,>75%',
            'daun_menguning' => 'required|in:<25%,25–45%,50–74%,>75%',
            'bercak_daun' => 'required|in:<25%,25–45%,50–74%,>75%',
            'daun_serangga' => 'required|in:<25%,25–45%,50–74%,>75%',
            'survival_rate' => 'required|numeric|between:0,100',
            'dokumentasi_monitoring' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $monitoring = Monitoring::create([
            'implementasi_id' => $implementasi_id,
            'jumlah_bibit_ditanam' => $request->jumlah_bibit_ditanam,
            'jumlah_bibit_mati' => $request->jumlah_bibit_mati,
            'diameter_batang' => $request->diameter_batang,
            'jumlah_daun' => $request->jumlah_daun,
            'daun_mengering' => $request->daun_mengering,
            'daun_layu' => $request->daun_layu,
            'daun_menguning' => $request->daun_menguning,
            'bercak_daun' => $request->bercak_daun,
            'daun_serangga' => $request->daun_serangga,
            'survival_rate' => $request->survival_rate,
            'dokumentasi_monitoring' => $request->dokumentasi_monitoring,
        ]);

        return response()->json([
            'message' => 'Monitoring created successfully',
            'monitoring' => $monitoring
        ], 201);
    }

    /**
     * @OA\Get(
     *   path="/api/forms",
     *   tags={"Dashboard"},
     *   summary="Get all forms by authenticated user",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Success")
     * )
     */
    public function getUserForms()
    {
        $user = auth()->user();
        $perencanaan = Perencanaan::where('user_id', $user->id)->with('implementasi.monitoring')->get();

        return response()->json([
            'user' => $user,
            'forms' => $perencanaan
        ]);
    }

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
    public function uploadDokumentasi(Request $request)
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
