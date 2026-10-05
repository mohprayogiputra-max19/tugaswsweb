<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    public function test_guest_is_redirected_to_login_from_admin_route(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_admin_route(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'user']))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_route(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeText('Dashboard Admin');
    }

    public function test_shared_dashboard_redirects_admin_to_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_kasir_cannot_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_kasir_can_access_kasir_dashboard(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSeeText('Dashboard Kasir');
    }

    public function test_shared_dashboard_redirects_kasir_to_kasir_dashboard(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->get(route('dashboard'))
            ->assertRedirect(route('kasir.dashboard'));
    }

    public function test_admin_cannot_access_kasir_dashboard(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('kasir.dashboard'))
            ->assertForbidden();
    }

    public function test_kasir_cannot_access_admin_user_management(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_parameter_role_middleware_rejects_a_different_role(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'user']))
            ->get(route('admin.role-check'))
            ->assertForbidden();
    }

    public function test_parameter_role_middleware_allows_the_required_role(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('admin.role-check'))
            ->assertOk()
            ->assertSeeText('Halaman dengan middleware parameter role.');
    }

    public function test_user_and_transaction_route_names_generate_urls_for_their_views(): void
    {
        $this->assertSame('/users/17/edit', route('users.edit', ['user' => 17], false));
        $this->assertSame('/users/17', route('users.update', ['user' => 17], false));

        $this->assertSame('/transaksi', route('transaksi.index', [], false));
        $this->assertSame('/transaksi/create', route('transaksi.create', [], false));
        $this->assertSame('/transaksi/17/edit', route('transaksi.edit', ['transaksi' => 17], false));
        $this->assertSame('/transaksi', route('transaksi.store', [], false));
        $this->assertSame('/transaksi/17', route('transaksi.update', ['transaksi' => 17], false));
        $this->assertSame('/transaksi/17', route('transaksi.destroy', ['transaksi' => 17], false));
    }

    public function test_user_and_transaction_views_render_without_missing_routes_or_parameters(): void
    {
        $userList = view('users.index', [
            'users' => collect([
                (object) [
                    'id' => 17,
                    'name' => 'Kasir Test',
                    'email' => 'kasir@example.test',
                    'phone' => null,
                    'role' => 'kasir',
                ],
            ]),
        ])->render();

        $this->assertStringContainsString(
            'href="'.route('users.edit', ['user' => 17]).'"',
            $userList
        );
        $this->assertStringContainsString(
            'action="'.route('users.destroy', 17).'"',
            $userList
        );

        $this->assertStringContainsString(
            route('transaksi.create'),
            view('transaksi.index', ['transaksi' => collect()])->render()
        );
        $this->assertStringContainsString(
            route('transaksi.store'),
            view('transaksi.create', [
                'users' => collect(),
                'errors' => new ViewErrorBag(),
            ])->render()
        );
    }
}
