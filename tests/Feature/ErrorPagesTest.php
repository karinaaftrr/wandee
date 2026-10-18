<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ErrorPagesTest extends TestCase
{
    public function test_404_page_renders_custom_wandee_view(): void
    {
        $response = $this->get('/halaman-yang-pasti-tidak-ada-12345');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Jejak Petualangan Tidak Ditemukan');
        $response->assertSee('Wandee');
    }

    public function test_403_page_renders_custom_wandee_view_for_non_admin(): void
    {
        $user = User::where('role', 'user')->first() ?? User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
        $response->assertSee('403');
        $response->assertSee('Akses Terbatas');
    }
}
