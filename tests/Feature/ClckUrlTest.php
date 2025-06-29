<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Link;
use App\Models\LinkVisit;

class ClckUrlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_short_link_and_return_code()
    {
        $response = $this->postJson('/api/links', [
            'original_url' => 'https://laravel.com/docs'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'short_url', 'code', 'original_url', 'created_at'
                 ]);

        // Проверим, что запись появилась в базе
        $this->assertDatabaseHas('links', [
            'original_url' => 'https://laravel.com/docs'
        ]);
        // Код должен быть длины 6
        $code = $response->json('code');
        $this->assertEquals(6, strlen($code));
    }

    /** @test */
    public function redirect_to_original_url_and_logs_visit()
    {
        // Создаем вручную ссылку
        $link = Link::create([
            'original_url' => 'https://laravel.com/docs',
            'code' => 'abc123'
        ]);
        // Выполняем запрос к сокращенному URL
        $response = $this->get('/abc123');
        $response->assertStatus(302);
        $response->assertRedirect('https://laravel.com/docs');

        // После редиректа должна сохраниться запись о визите
        $this->assertDatabaseHas('link_visits', [
            'link_id' => $link->id,
            // IP-адрес можно проверить через $response->baseResponse->getContent() или во всем списке
        ]);
        $this->assertEquals(1, $link->visits()->count());
    }


    /** @test */
    public function stats_endpoint_returns_correct_data()
    {
        // Создаем ссылку
        $link = Link::create([
            'original_url' => 'https://laravel.com/docs',
            'code' => 'xyz789'
        ]);
        // Имитируем 3 посещения
        LinkVisit::factory()->create(['link_id' => $link->id, 'ip_address' => '127.0.0.1']);
        LinkVisit::factory()->create(['link_id' => $link->id, 'ip_address' => '127.0.0.1']);
        LinkVisit::factory()->create(['link_id' => $link->id, 'ip_address' => '127.0.0.1']);

        // Запрос к статистике с токеном
        $token = config('app.api_stats_token');
        $response = $this->getJson('/api/links/xyz789/stats', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200)
                 ->assertJsonFragment(['short_code' => 'xyz789'])
                 ->assertJsonFragment(['clicks_count' => 3]);
    }


}
