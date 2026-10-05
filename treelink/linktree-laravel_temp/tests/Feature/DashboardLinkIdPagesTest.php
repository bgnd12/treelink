<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardLinkIdPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_linkid_and_messages_pages_load_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.linkid.discover'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('dashboard.messages'))
            ->assertOk();
    }
}
