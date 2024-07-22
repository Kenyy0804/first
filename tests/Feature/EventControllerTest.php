<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Models\User; // Userモデルをインポート
use Illuminate\Support\Facades\DB;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        // テストデータの作成
        $today = Carbon::today();

        // テストユーザーの作成
        $user = User::factory()->create();
        $user = User::factory()->create(['role' => '5']);

        $event1 = DB::table('events')->insertGetId([
            'name' => 'Event 1', // 必須カラム
            'information' => 'Information for Event 1', // 必須カラム
            'max_people' => 100, // 必須カラム
            'start_date' => $today->copy()->addDays(1),
            'end_date' => $today->copy()->addDays(2),
            'is_visible' => true, // 必須カラム
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $event2 = DB::table('events')->insertGetId([
            'name' => 'Event 2', // 必須カラム
            'information' => 'Information for Event 2', // 必須カラム
            'max_people' => 200, // 必須カラム
            'start_date' => $today->copy()->addDays(2),
            'end_date' => $today->copy()->addDays(3),
            'is_visible' => true, // 必須カラム
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('reservations')->insert([
            'event_id' => $event1,
            'user_id' => $user->id,
            'number_of_people' => 5,
            'canceled_date' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('reservations')->insert([
            'event_id' => $event2,
            'user_id' => $user->id,
            'number_of_people' => 3,
            'canceled_date' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ユーザーを認証してリクエストを実行
        $response = $this->actingAs($user)->get('/manager/events');

        // レスポンスの検証
        $response->assertStatus(200);
        $response->assertViewIs('manager.events.index');
        $response->assertViewHas('events');

        $events = $response->viewData('events');

        $this->assertEquals(2, $events->total()); // ページネーションの合計件数が正しいか
        $this->assertEquals($event1, $events->items()[0]->id); // 最初のイベントのIDが正しいか
        $this->assertEquals($event2, $events->items()[1]->id); // 次のイベントのIDが正しいか
    }
}
