<?php

namespace Tests\Feature\Admin;

use App\Models\Car;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateInspectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_inspection_with_camel_case_fields(): void
    {
        $this->withoutMiddleware();

        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $car = Car::create([
            'user_id' => $user->id,
            'plate_number' => 'ABC-1234',
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => '2023',
            'color' => 'White',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'pickup_required' => false,
            'status' => 'pending',
            'total_cost' => 0,
        ]);

        $response = $this->postJson("/api/admin/orders/{$order->id}/inspections", [
            'inspectionDate' => '2026-06-01',
            'type' => 'initial',
            'findings' => 'Initial inspection completed',
            'estimatedCost' => 120,
            'notes' => 'No issues found',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.order_id', (string) $order->id);
        $response->assertJsonPath('data.type', 'initial');

        $this->assertDatabaseHas('inspections', [
            'order_id' => $order->id,
            'type' => 'initial',
            'findings' => 'Initial inspection completed',
            'estimated_cost' => 120,
            'notes' => 'No issues found',
        ]);
    }
}
