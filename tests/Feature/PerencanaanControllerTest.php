<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Perencanaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerencanaanControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_create_perencanaan_with_valid_data(): void
    {
        $data = [
            'nama_perusahaan' => 'PT Test Company',
            'nama_pic' => 'John Doe',
            'narahubung' => '+6281234567890',
            'jenis_kegiatan' => 'Planting Mangrove',
            'lokasi' => 'Test Location',
            'jumlah_bibit' => 1000,
            'jenis_bibit' => 'Test Species',
            'tanggal_pelaksanaan' => now()->addDays(30)->format('Y-m-d'),
            'lat' => '-6.2088',
            'long' => '106.8456',
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/perencanaan', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'nama_perusahaan',
                    'status',
                    'coordinates',
                ]
            ]);

        $this->assertDatabaseHas('perencanaans', [
            'user_id' => $this->user->id,
            'nama_perusahaan' => 'PT Test Company',
        ]);
    }

    /** @test */
    public function user_cannot_create_perencanaan_with_past_date(): void
    {
        $data = [
            'nama_perusahaan' => 'PT Test Company',
            'nama_pic' => 'John Doe',
            'narahubung' => '+6281234567890',
            'jenis_kegiatan' => 'Planting Mangrove',
            'lokasi' => 'Test Location',
            'jumlah_bibit' => 1000,
            'jenis_bibit' => 'Test Species',
            'tanggal_pelaksanaan' => now()->subDays(1)->format('Y-m-d'),
            'lat' => '-6.2088',
            'long' => '106.8456',
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/perencanaan', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tanggal_pelaksanaan']);
    }
}
