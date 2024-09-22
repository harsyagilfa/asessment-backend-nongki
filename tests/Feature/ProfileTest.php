<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected function authenticate() {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return ['Authorization' => "Bearer $token", 'user' => $user];
    }
    public function test_user_create_profile()
    {
        $auth = $this->authenticate();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/profile', [
            'nama_lengkap' => 'Gilang Harsya Fadillah',
            'alamat' => 'Jalan Cinta Sejati',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
        ], ['Authorization' => $auth['Authorization']]);
        $response->assertStatus(201)
            ->assertJson([
                'nama_lengkap' => 'Gilang Harsya Fadillah',
                'alamat' => 'Jalan cinta sejati',
                'jenis_kelamin' => 'Laki-laki',
                'status_pernikahan' => 'Belum Menikah',
            ]);
    }
    public function test_user_view_profile()
    {
        $auth = $this->authenticate();
        $profile = Profile::factory()->create([
            'user_id' => $auth['user']->id,
            'nama_lengkap' => 'Gilang Harsya',
            'alamat' => 'Jalan Cinta Sejati',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Belum Menikah',
        ]);
        $response = $this->getJson('/api/profile' .$auth['Authorization']);
        $response->assertStatus(200)
            ->assertJson([
                'nama_lengkap' => 'Gilang Harsya',
                'alamat' => 'Jalan Cinta Sejati',
                'jenis_kelamin' => 'Laki-laki',
                'status_pernikahan' => 'Belum Menikah',
            ]);

    }
    public function test_user_update_profile()
    {
        $auth = $this->authenticate();
        $profile = Profile::factory()->create([
            'user_id' => $auth['user']->id,
        ]);
        $response = $this->putJson('/api/profile', [
            'nama_lengkap' => 'Fadillah Harsya',
            'alamat' => 'Jalan Cinta Sejati',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Menikah',
        ], ['Authorization' => $auth['Authorization']]);
        $response->assertStatus(200)
        ->assertJson([
            'nama_lengkap' => 'Fadillah Harsya',
            'alamat' => 'Jalan Cinta Sejati',
            'jenis_kelamin' => 'Laki-laki',
            'status_pernikahan' => 'Menikah',
        ]);
    }
    public function test_user_delete_profile()
    {
        $auth = $this->authenticate();

        $profile = Profile::factory()->create([
        'user_id' => $auth['user']->id,
        ]);
        $response = $this->deleteJson('/api/profile', [], ['Authorization' => $auth['Authorization']]); // Perbaikan di sini
        $response->assertStatus(200)
        ->assertJson(['message' => 'Profile Berhasil dihapus']);

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }
}
