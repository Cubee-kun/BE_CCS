<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerencanaanRequest;
use App\Http\Requests\UpdatePerencanaanRequest;
use App\Http\Resources\PerencanaanResource;
use App\Services\PerencanaanService;
use App\Services\BlockchainService;
use Illuminate\Http\JsonResponse;

class PerencanaanController extends Controller
{
    public function __construct(
        private PerencanaanService $perencanaanService,
        private BlockchainService $blockchainService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/perencanaan",
     *     tags={"Perencanaan"},
     *     summary="Get all user perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/PerencanaanCollection")
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $perencanaan = $this->perencanaanService->getUserPerencanaan(
            auth()->id(),
            request('per_page', 15)
        );

        return response()->json([
            'data' => PerencanaanResource::collection($perencanaan->items()),
            'meta' => [
                'current_page' => $perencanaan->currentPage(),
                'last_page' => $perencanaan->lastPage(),
                'per_page' => $perencanaan->perPage(),
                'total' => $perencanaan->total(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/perencanaan",
     *     tags={"Perencanaan"},
     *     summary="Create new perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePerencanaanRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PerencanaanResource")
     *     ),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function store(StorePerencanaanRequest $request): JsonResponse
    {
        try {
            $perencanaan = $this->perencanaanService->create(
                $request->validated(),
                auth()->id()
            );

            // Store on blockchain asynchronously
            $this->blockchainService->storePerencanaanAsync($perencanaan);

            return response()->json([
                'message' => 'Perencanaan created successfully',
                'data' => new PerencanaanResource($perencanaan)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create perencanaan',
                'error' => app()->isProduction() ? 'Internal server error' : $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/perencanaan/{id}",
     *     tags={"Perencanaan"},
     *     summary="Get perencanaan by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/PerencanaanResource")
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $perencanaan = $this->perencanaanService->findUserPerencanaan($id, auth()->id());

        if (!$perencanaan) {
            return response()->json(['message' => 'Perencanaan not found'], 404);
        }

        return response()->json([
            'data' => new PerencanaanResource($perencanaan->load(['implementasi', 'user']))
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/perencanaan/{id}",
     *     tags={"Perencanaan"},
     *     summary="Update perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePerencanaanRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PerencanaanResource")
     *     ),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function update(UpdatePerencanaanRequest $request, int $id): JsonResponse
    {
        try {
            $perencanaan = $this->perencanaanService->update(
                $id,
                $request->validated(),
                auth()->id()
            );

            if (!$perencanaan) {
                return response()->json(['message' => 'Perencanaan not found'], 404);
            }

            return response()->json([
                'message' => 'Perencanaan updated successfully',
                'data' => new PerencanaanResource($perencanaan)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update perencanaan',
                'error' => app()->isProduction() ? 'Internal server error' : $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/perencanaan/{id}",
     *     tags={"Perencanaan"},
     *     summary="Delete perencanaan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Deleted successfully"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->perencanaanService->delete($id, auth()->id());

        if (!$deleted) {
            return response()->json(['message' => 'Perencanaan not found'], 404);
        }

        return response()->json(['message' => 'Perencanaan deleted successfully']);
    }
}
