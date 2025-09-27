<?php

namespace App\Services;

use App\Models\Perencanaan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerencanaanService
{
    public function getUserPerencanaan(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Perencanaan::where('user_id', $userId)
            ->with(['implementasi', 'user:id,name'])
            ->latest()
            ->paginate($perPage);
    }

    public function findUserPerencanaan(int $id, int $userId): ?Perencanaan
    {
        return Perencanaan::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function create(array $data, int $userId): Perencanaan
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['user_id'] = $userId;

            $perencanaan = Perencanaan::create($data);

            Log::info('Perencanaan created', [
                'perencanaan_id' => $perencanaan->id,
                'user_id' => $userId,
                'company' => $data['nama_perusahaan']
            ]);

            return $perencanaan;
        });
    }

    public function update(int $id, array $data, int $userId): ?Perencanaan
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $perencanaan = $this->findUserPerencanaan($id, $userId);

            if (!$perencanaan) {
                return null;
            }

            $perencanaan->update($data);

            Log::info('Perencanaan updated', [
                'perencanaan_id' => $id,
                'user_id' => $userId
            ]);

            return $perencanaan->fresh();
        });
    }

    public function delete(int $id, int $userId): bool
    {
        return DB::transaction(function () use ($id, $userId) {
            $perencanaan = $this->findUserPerencanaan($id, $userId);

            if (!$perencanaan) {
                return false;
            }

            $deleted = $perencanaan->delete();

            if ($deleted) {
                Log::info('Perencanaan deleted', [
                    'perencanaan_id' => $id,
                    'user_id' => $userId
                ]);
            }

            return $deleted;
        });
    }
}
