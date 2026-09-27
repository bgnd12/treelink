<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditorDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        $user = User::factory()->create(['username' => 'editor_test']);
        $user->profile()->create(['bio' => 'Bio awal']);
        $user->links()->create(['title' => 'Instagram', 'url' => 'https://instagram.com/x', 'icon' => 'instagram', 'position' => 1]);
        $user->links()->create(['title' => 'YouTube', 'url' => 'https://youtube.com/x', 'icon' => 'youtube', 'position' => 2]);

        return $user;
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_editor_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->user())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Content', false)
            ->assertSee('Design', false)
            ->assertSee('Enhance', false);
    }

    public function test_design_settings_can_be_saved_via_json(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->putJson('/dashboard/settings', [
                'theme' => 'ocean',
                'button_style' => 'pill',
                'font' => 'serif',
                'layout' => 'hero',
                'wallpaper_type' => 'solid',
                'bg_color' => '#123456',
                'button_radius' => 20,
                'button_shadow' => 1,
                'button_border' => 1,
                'show_social' => 0,
            ])
            ->assertOk()
            ->assertJsonPath('design.theme', 'ocean');

        $profile = $user->profile()->firstOrFail();

        $this->assertSame('ocean', $profile->theme);
        $this->assertSame('pill', $profile->button_style);
        $this->assertSame('hero', $profile->settings()['layout']);
        $this->assertSame('solid', $profile->settings()['wallpaper_type']);
        $this->assertSame('#123456', $profile->settings()['bg_color']);
        $this->assertFalse($profile->settings()['show_social']);
    }

    public function test_profile_header_can_be_updated_via_json(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->putJson('/dashboard/profile', [
                'name' => 'Editor Tester',
                'username' => 'editor_fixed',
                'display_name' => 'Tester',
                'bio' => 'Halo dunia',
                'layout' => 'banner',
            ])
            ->assertOk()
            ->assertJsonPath('profile.username', 'editor_fixed');

        $this->assertSame('banner', $user->fresh()->profile->settings()['layout']);
    }

    public function test_login_redirects_to_editor_dashboard(): void
    {
        $user = $this->user();

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');
    }

    public function test_links_crud_and_reorder_via_json(): void
    {
        $user = $this->user();

        $created = $this->actingAs($user)
            ->postJson('/dashboard/links', [
                'title' => 'WhatsApp',
                'url' => 'https://wa.me/62812',
                'icon' => 'whatsapp',
            ])
            ->assertCreated()
            ->json('link');

        $this->assertSame('WhatsApp', $created['title']);

        $link = Link::findOrFail($created['id']);

        $this->actingAs($user)
            ->patchJson('/dashboard/links/'.$link->id.'/toggle')
            ->assertOk()
            ->assertJsonPath('link.is_active', false);

        $this->actingAs($user)
            ->putJson('/dashboard/links/'.$link->id, [
                'title' => 'WA',
                'url' => 'https://wa.me/62812',
            ])
            ->assertOk()
            ->assertJsonPath('link.title', 'WA');

        $this->actingAs($user)
            ->postJson('/dashboard/links/reorder', [
                'order' => [$link->id, $user->links[0]->id, $user->links[1]->id],
            ])
            ->assertOk();

        $this->assertSame(1, $link->refresh()->position);

        $this->actingAs($user)
            ->deleteJson('/dashboard/links/'.$link->id)
            ->assertOk();

        $this->assertDatabaseMissing('links', ['id' => $link->id]);
    }

    public function test_links_toggle_and_delete_work_with_method_spoofing_like_the_js(): void
    {
        $user = $this->user();
        $link = $user->links()->first();

        $this->actingAs($user)
            ->postJson('/dashboard/links/'.$link->id.'/toggle', ['_method' => 'PATCH'])
            ->assertOk()
            ->assertJsonPath('link.is_active', false);

        $this->assertFalse($link->refresh()->is_active);

        $this->actingAs($user)
            ->postJson('/dashboard/links/'.$link->id, ['_method' => 'DELETE'])
            ->assertOk();

        $this->assertDatabaseMissing('links', ['id' => $link->id]);
    }

    public function test_inactive_links_and_user_data_are_reflected_on_public_profile(): void
    {
        $user = $this->user();
        $user->profile()->update([
            'display_name' => 'Editor Tester',
            'bio' => 'Bio baru',
            'social_links' => [
                'instagram' => 'https://instagram.com/editor_test',
                'email' => 'editor_test@example.com',
            ],
        ]);

        $active = $user->links()->first();
        $inactive = $user->links()->skip(1)->first();
        $inactive->update(['is_active' => false]);

        $this->get('/'.$user->username)
            ->assertOk()
            ->assertSee('Editor Tester')
            ->assertSee('Bio baru')
            ->assertSee('editor_test@example.com')
            ->assertSee($active->title)
            ->assertDontSee($inactive->title);

        $this->get(route('public.link.redirect', ['username' => $user->username, 'link' => $active->id]))
            ->assertRedirect($active->url);

        $this->assertSame(1, $active->refresh()->clicks_count);

        $this->get(route('public.link.redirect', ['username' => $user->username, 'link' => $inactive->id]))
            ->assertNotFound();
    }

    public function test_design_settings_appear_on_public_profile(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->putJson('/dashboard/settings', [
                'theme' => 'sunset',
                'button_style' => 'pill',
                'font' => 'serif',
                'layout' => 'banner',
                'wallpaper_type' => 'solid',
                'bg_color' => '#123456',
                'show_social' => 0,
                'show_share' => 0,
            ])
            ->assertOk();

        $this->get('/'.$user->username)
            ->assertOk()
            ->assertSee('linear-gradient(135deg, #f97316, #f43f5e)', false)
            ->assertDontSee('Bagikan Halaman Ini');
    }

    public function test_custom_wallpaper_and_link_icon_are_rendered(): void
    {
        $user = $this->user();
        $link = $user->links()->first();

        $this->actingAs($user)
            ->putJson('/dashboard/settings', [
                'wallpaper_type' => 'custom',
                'custom_wallpaper_url' => 'https://example.com/wallpaper.jpg',
            ])
            ->assertOk();

        $this->actingAs($user)
            ->putJson('/dashboard/links/'.$link->id, [
                'title' => 'Instagram Custom',
                'url' => 'https://instagram.com/custom',
                'icon' => 'instagram',
            ])
            ->assertOk();

        $this->get('/'.$user->username)
            ->assertOk()
            ->assertSee('wallpaper.jpg', false)
            ->assertSee('Instagram Custom')
            ->assertSee('<rect x="3" y="3" width="18" height="18" rx="5"/>', false);
    }

    public function test_enhance_settings_are_saved_and_rendered_on_public_profile(): void
    {
        $user = $this->user();
        $featured = $user->links()->first();

        $this->actingAs($user)
            ->putJson('/dashboard/settings', [
                'social_links' => [
                    'instagram' => 'https://instagram.com/editor_test',
                    'tiktok' => 'https://tiktok.com/@editor_test',
                ],
                'featured_link_id' => $featured->id,
                'animations_enabled' => 1,
                'seo_title' => 'Editor Test SEO',
                'seo_description' => 'Deskripsi SEO editor test.',
            ])
            ->assertOk()
            ->assertJsonPath('design.settings.featured_link_id', $featured->id)
            ->assertJsonPath('socials.instagram', 'https://instagram.com/editor_test');

        $this->assertSame('Editor Test SEO', $user->fresh()->profile->settings()['seo_title']);

        $this->get('/'.$user->username)
            ->assertOk()
            ->assertSee('<title>Editor Test SEO</title>', false)
            ->assertSee('Deskripsi SEO editor test.', false)
            ->assertSee('⭐', false)
            ->assertSee('editor_test', false);
    }

    public function test_avatar_upload_is_persisted_to_storage(): void
    {
        Storage::fake('public');

        $user = $this->user();

        $this->actingAs($user)
            ->post('/dashboard/profile', [
                '_method' => 'PUT',
                'name' => 'Editor Tester',
                'username' => 'editor_test',
                'display_name' => 'Tester',
                'bio' => 'Halo',
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 96, 96),
            ])
            ->assertRedirect();

        $profile = $user->fresh()->profile()->first();

        $this->assertStringStartsWith('storage/avatars/', (string) $profile->avatar_url);

        $storedPath = str_replace('storage/', '', (string) $profile->avatar_url);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_landing_page_still_works(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_all_editor_panels_are_rendered(): void
    {
        $this->actingAs($this->user())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Add Link')
            ->assertSee('Foto Profil')
            ->assertSee('Wallpaper')
            ->assertSee('Bagikan Halaman')
            ->assertSee('Pratinjau')
            ->assertSee('Simpan Design');
    }
}
