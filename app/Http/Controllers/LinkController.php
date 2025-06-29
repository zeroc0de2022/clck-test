<?php
namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkVisit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Создание короткой ссылки
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Валидация входных данных
        $request->validate([
            'original_url' => 'required|url'
        ]);

        // Генерация уникального кода длиной 6 символов
        $code = Str::random(6);
        // Проверим на уникальность в базе (вдруг сгенерированный код уже существует)
        while (Link::where('code', $code)->exists()) {
            $code = Str::random(6);
        }

        // Создание новой записи ссылки в базе
        $link = Link::create([
            'original_url' => $request->input('original_url'),
            'code' => $code
        ]);

        // Формирование ответа с сокращенным URL
        $shortUrl = url($code); // построим полный URL на основе текущего хоста
        return response()->json([
            'short_url' => $shortUrl,
            'code' => $code,
            'original_url' => $link->original_url,
            'created_at' => $link->created_at
        ], 201);
    }


    /**
     * Перенаправление по сокращенному URL
     * @param Request $request
     * @param $code
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function redirect(Request $request, $code)
    {
        // Ищем ссылку по коду. Если не найдена – 404
        $link = Link::where('code', $code)->first();
        if (!$link) {
            abort(404, 'Short link not found');
        }
        // Запись перехода в базу
        LinkVisit::create([
            'link_id'   => $link->id,
            'ip_address'=> $request->ip()
        ]);
        // Перенаправляем на оригинальный URL
        return redirect($link->original_url);
    }

    /**
     * Получение статистики
     * @param $code
     * @return JsonResponse
     */
    public function stats($code): JsonResponse
    {
        $link = Link::where('code', $code)->first();
        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json([
            'original_url' => $link->original_url,
            'short_code'   => $link->code,
            'created_at'   => $link->created_at,// Дата создания ссылки
            'clicks_count' => $link->visits()->count() // Количество переходов
        ]);
    }
}
