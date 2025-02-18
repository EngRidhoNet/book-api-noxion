<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $editorUser;
    protected $viewerUser;
    protected $testBuku;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Create test users with different roles
        $this->adminUser = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        $this->editorUser = User::factory()->create([
            'role_id' => Role::where('name', 'editor')->first()->id
        ]);

        $this->viewerUser = User::factory()->create([
            'role_id' => Role::where('name', 'viewer')->first()->id
        ]);

        // Create a test book
        $this->testBuku = Buku::factory()->create([
            'judul' => 'Test Book',
            'penulis' => 'Test Author',
            'tahun_terbit' => 2024,
            'deskripsi' => 'Test Description'
        ]);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role_id' => Role::where('name', 'viewer')->first()->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role_id'],
                'token'
            ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role_id' => Role::where('name', 'viewer')->first()->id
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role_id'],
                'token'
            ]);
    }

    public function test_viewer_can_list_books()
    {
        Sanctum::actingAs($this->viewerUser);

        $response = $this->getJson('/api/buku');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'judul' => 'Test Book'
            ]);
    }

    public function test_viewer_cannot_create_book()
    {
        Sanctum::actingAs($this->viewerUser);

        $response = $this->postJson('/api/buku', [
            'judul' => 'New Book',
            'penulis' => 'New Author',
            'tahun_terbit' => 2024,
            'deskripsi' => 'New Description'
        ]);

        $response->assertStatus(403);
    }

    public function test_editor_can_create_book()
    {
        Sanctum::actingAs($this->editorUser);

        $response = $this->postJson('/api/buku', [
            'judul' => 'New Book',
            'penulis' => 'New Author',
            'tahun_terbit' => 2024,
            'deskripsi' => 'New Description'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'judul' => 'New Book'
            ]);
    }

    public function test_editor_can_update_book()
    {
        Sanctum::actingAs($this->editorUser);

        $response = $this->putJson("/api/buku/{$this->testBuku->id}", [
            'judul' => 'Updated Book',
            'penulis' => 'Updated Author',
            'tahun_terbit' => 2024,
            'deskripsi' => 'Updated Description'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'judul' => 'Updated Book'
            ]);
    }

    public function test_editor_cannot_delete_book()
    {
        Sanctum::actingAs($this->editorUser);

        $response = $this->deleteJson("/api/buku/{$this->testBuku->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_book()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->deleteJson("/api/buku/{$this->testBuku->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('buku', ['id' => $this->testBuku->id]);
    }

    public function test_invalid_login_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_registration_validation()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'role_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role_id']);
    }

    public function test_book_validation()
    {
        Sanctum::actingAs($this->editorUser);

        $response = $this->postJson('/api/buku', [
            'judul' => '',
            'penulis' => '',
            'tahun_terbit' => 'invalid',
            'deskripsi' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'penulis', 'tahun_terbit', 'deskripsi']);
    }
}
