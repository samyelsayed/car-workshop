<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardDataFinalSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $timestamp = $now->timestamp;

        // 1. إضافة مستخدمين جدد (أدمن وعملاء)
        $adminIds = [];
        $adminData = [
            ['first_name' => 'أدمن', 'last_name' => 'داشبورد 1', 'email' => "admin_final_{$timestamp}_1@workshop.com", 'password' => Hash::make('password'), 'role' => 'admin', 'created_at' => $now->copy()->subMonths(1)],
            ['first_name' => 'أدمن', 'last_name' => 'داشبورد 2', 'email' => "admin_final_{$timestamp}_2@workshop.com", 'password' => Hash::make('password'), 'role' => 'admin', 'created_at' => $now->copy()->subWeeks(2)],
        ];

        foreach ($adminData as $admin) {
            $adminIds[] = DB::table('users')->insertGetId($admin);
        }

        $userIds = [];
        $userData = [
            ['first_name' => 'عميل', 'last_name' => 'إضافي 1', 'email' => "user_final_{$timestamp}_1@gmail.com", 'password' => Hash::make('password'), 'role' => 'user', 'created_at' => $now->copy()->subDays(4)],
            ['first_name' => 'عميل', 'last_name' => 'إضافي 2', 'email' => "user_final_{$timestamp}_2@gmail.com", 'password' => Hash::make('password'), 'role' => 'user', 'created_at' => $now->copy()->subDays(1)],
            ['first_name' => 'عميل', 'last_name' => 'إضافي 3', 'email' => "user_final_{$timestamp}_3@gmail.com", 'password' => Hash::make('password'), 'role' => 'user', 'created_at' => $now],
        ];

        foreach ($userData as $user) {
            $userIds[] = DB::table('users')->insertGetId($user);
        }

        // 1.5 تأمين وجود سيارات (Cars) لربطها بالأوردرات فورياً
        $carIds = [];
        $brands = ['تويوتا', 'هيونداي', 'كيا'];
        $models = ['كورولا', 'النترا', 'سيراتو'];

        foreach ($userIds as $index => $userId) {
            $existingCar = DB::table('cars')->where('user_id', $userId)->first();
            if ($existingCar) {
                $carIds[$userId] = $existingCar->id;
            } else {
                $carIds[$userId] = DB::table('cars')->insertGetId([
                    'user_id'      => $userId,
                    'plate_number' => 'أ ب ج ' . rand(111, 999),
                    'brand'        => $brands[$index % 3],
                    'model'        => $models[$index % 3],
                    'year'         => rand(2018, 2024),
                    'color'        => 'أسود ميتاليك',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }

        // 2. إضافة خدمات جديدة وعمل Map للأسماء والأسعار بناءً على الـ ID
        $serviceIds = [];
        $serviceNamesMap = [];
        $servicePricesMap = [];

        $servicesData = [
            ['name' => 'شحن فريون وتطهير التكييف', 'base_price' => 600.00, 'description' => 'شحن فريون أمريكي أصلي وتطهير التكييف.', 'is_active' => true, 'created_at' => $now],
            ['name' => 'الكشف بالكمبيوتر', 'base_price' => 400.00, 'description' => 'فحص شامل لجميع أعطال السيارة بالكمبيوتر.', 'is_active' => true, 'created_at' => $now],
            ['name' => 'طقم تيل فرامل أمامي', 'base_price' => 850.50, 'description' => 'تغيير تيل الفرامل الأمامي مع مراجعة الطنابير.', 'is_active' => true, 'created_at' => $now],
            ['name' => 'تغيير زيت المحرك 10 آلاف كم', 'base_price' => 1250.00, 'description' => 'تغيير زيت كامل 10 آلاف مع الفلتر الأصلي.', 'is_active' => true, 'created_at' => $now],
        ];

        foreach ($servicesData as $service) {
            $insertedId = DB::table('services')->insertGetId($service);
            $serviceIds[] = $insertedId;
            $serviceNamesMap[$insertedId] = $service['name'];
            $servicePricesMap[$insertedId] = $service['base_price'];
        }

        // 3. إضافة طلبات (Orders) متوافقة وتفاصيلها (Order Items) بكامل الحقول
        $ordersData = [
            ['user_id' => $userIds[0], 'car_id' => $carIds[$userIds[0]], 'service_id' => $serviceIds[0], 'status' => 'completed', 'total_cost' => 600.00, 'created_at' => $now->copy()->subMonth()],
            ['user_id' => $userIds[1], 'car_id' => $carIds[$userIds[1]], 'service_id' => $serviceIds[2], 'status' => 'completed', 'total_cost' => 850.50, 'created_at' => $now->copy()->subDays(3)],
            ['user_id' => $userIds[2], 'car_id' => $carIds[$userIds[2]], 'service_id' => $serviceIds[1], 'status' => 'cancelled', 'total_cost' => 400.00, 'created_at' => $now->copy()->subDays(2)],
            ['user_id' => $userIds[0], 'car_id' => $carIds[$userIds[0]], 'service_id' => $serviceIds[0], 'status' => 'completed', 'total_cost' => 600.00, 'created_at' => $now->copy()->subHours(1)],
            ['user_id' => $userIds[1], 'car_id' => $carIds[$userIds[1]], 'service_id' => $serviceIds[3], 'status' => 'pending', 'total_cost' => 1250.00, 'created_at' => $now->copy()->subHours(4)],
        ];

        foreach ($ordersData as $order) {
            // أ - إنشاء الأوردر الرئيسي
            $orderId = DB::table('orders')->insertGetId([
                'user_id'         => $order['user_id'],
                'car_id'          => $order['car_id'],
                'status'          => $order['status'],
                'total_cost'      => $order['total_cost'],
                'pickup_required' => false,
                'created_at'      => $order['created_at'],
                'updated_at'      => $order['created_at'],
            ]);

            // ب - حساب السعر الفردي والإجمالي للعنصر
            $unitPrice = $servicePricesMap[$order['service_id']] ?? $order['total_cost'];
            $quantity = 1;
            $subtotal = $unitPrice * $quantity;

            // جـ - إدخال تفاصيل الـ Order Item كاملة متكاملة
            DB::table('order_items')->insert([
                'order_id'      => $orderId,
                'service_id'    => $order['service_id'],
                'service_name'  => $serviceNamesMap[$order['service_id']] ?? 'خدمة صيانة سيارات',
                'service_image' => null,
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'subtotal'      => $subtotal,
                'notes'         => 'تم الفحص والصيانة الدورية بنجاح',
                'created_at'    => $order['created_at'],
                'updated_at'    => $order['created_at'],
            ]);
        }
    }
}
