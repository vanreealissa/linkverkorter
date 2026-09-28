<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_link_gets_a_random_code(): void
    {
        $this->post(route('links.store'), ['url' => 'https://laravel.com/docs'])
            ->assertRedirect();

        $link = Link::sole();
        $this->assertMatchesRegularExpression('/^[A-Za-z2-9]{6}$/', $link->code);
        $this->assertSame('https://laravel.com/docs', $link->url);
    }

    public function test_https_is_added_when_missing(): void
    {
        $this->post(route('links.store'), ['url' => 'voorbeeld.nl/pagina']);

        $this->assertSame('https://voorbeeld.nl/pagina', Link::sole()->url);
    }

    public function test_a_custom_code_can_be_chosen(): void
    {
        $this->post(route('links.store'), ['url' => 'https://example.com', 'code' => 'zomeractie'])
            ->assertRedirect(route('links.show', 'zomeractie'));
    }

    public function test_codes_must_be_unique_and_not_reserved(): void
    {
        Link::factory()->create(['code' => 'bezet']);

        $this->post(route('links.store'), ['url' => 'https://example.com', 'code' => 'bezet'])->assertSessionHasErrors('code');
        $this->post(route('links.store'), ['url' => 'https://example.com', 'code' => 'api'])->assertSessionHasErrors('code');
        $this->post(route('links.store'), ['url' => 'https://example.com', 'code' => 'met spatie'])->assertSessionHasErrors('code');
    }

    public function test_only_web_urls_are_accepted(): void
    {
        $this->post(route('links.store'), ['url' => 'javascript:alert(1)'])->assertSessionHasErrors('url');
        $this->post(route('links.store'), ['url' => 'ftp://example.com/bestand'])->assertSessionHasErrors('url');
    }

    public function test_visiting_a_short_link_redirects_and_counts_the_click(): void
    {
        $link = Link::factory()->create(['code' => 'docs', 'url' => 'https://laravel.com/docs']);

        $this->get('/docs', ['Referer' => 'https://www.linkedin.com/feed/'])
            ->assertRedirect('https://laravel.com/docs');

        $this->assertSame(1, $link->fresh()->clicks_count);
        $this->assertSame('www.linkedin.com', $link->clicks()->sole()->referrer_host);
    }

    public function test_unknown_codes_return_404(): void
    {
        $this->get('/bestaatniet')->assertNotFound();
    }

    public function test_statistics_page_shows_the_link(): void
    {
        $link = Link::factory()->create(['code' => 'stats']);
        $link->clicks()->create(['referrer_host' => 'github.com']);

        $this->get(route('links.show', $link))->assertOk()->assertSee('github.com');
    }

    public function test_api_creates_a_link(): void
    {
        $this->postJson('/api/links', ['url' => 'https://react.dev', 'code' => 'react'])
            ->assertCreated()
            ->assertJsonPath('data.code', 'react')
            ->assertJsonPath('data.short_url', url('react'))
            ->assertJsonPath('data.clicks', 0);
    }

    public function test_api_returns_validation_errors_as_json(): void
    {
        $this->postJson('/api/links', ['url' => 'geen link'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('url');
    }

    public function test_api_shows_click_count(): void
    {
        Link::factory()->create(['code' => 'teller'])->forceFill(['clicks_count' => 7])->save();

        $this->getJson('/api/links/teller')->assertOk()->assertJsonPath('data.clicks', 7);
    }
}
