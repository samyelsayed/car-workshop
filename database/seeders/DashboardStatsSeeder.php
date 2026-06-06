<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardStatsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $timestamp = $now->timestamp;

        // 1. إضافة مستخدمين جدد (أدمن وعملاء) متوافق مع first_name و last_name
        $adminIds = [];
        $adminData = [
            ['first_name' => 'أدمن', 'last_name' => 'داشبورد 1', 'email' => "admin_dash_{$timestamp}_1@workshop.com", 'password' => Hash::make('password'), 'role' => 'admin', 'created_at' => $now->copy()->subMonths(1)],
            ['first_name' => 'أدمن', 'last_name' => 'داشبورد 2', 'email' => "admin_dash_{$timestamp}_2@workshop.com", 'password' => Hash::make('password'), 'role' => 'admin', 'created_at' => $now->copy()->subWeeks(2)],
        ];

        foreach ($adminData as $admin) {
            $adminIds[] = DB::table('users')->insertGetId($admin);
        }

        $userIds = [];
        $userData = [
            ['first_name' => 'عميل', 'last_name' => 'إضافي 1', 'email' => "user_dash_{$timestamp}_1@gmail.com", 'password' => Hash::make('password'), 'role' => 'user', 'created_at' => $now->copy()->subDays(4)],
            ['first_name' => 'عميل', 'last_name' => 'إضافي 2', 'email' => "user_dash_{$timestamp}_2@gmail.com", 'password' => Hash::make('password'), 'role' => 'user', 'created_at' => $now->copy()->subDays(1)],
            ['first_name' => 'عميل', 'last_name' => 'إضافي 3', 'email' => "user_dash_{$timestamp}_3@gmail.com", 'password' => Hash::make('password'), 'role' => 'user', 'created_at' => $now],
        ];

        foreach ($userData as $user) {
            $userIds[] = DB::table('users')->insertGetId($user);
        }

        // 2. إضافة خدمات جديدة مع مراعاة الـ description والـ is_active إجباريًا هنا
        $serviceIds = [];
        $servicesData = [
            [
                'name' => 'شحن فريون وتطهير التكييف',
                'base_price' => 600.00,
                'description' => 'شحن فريون أمريكي أصلي مع تنظيف مجاري الهواء وتطهير التكييف.',
                'is_active' => true,
                'created_at' => $now
            ],
            [
                'name' => 'الكشف بالكمبيوتر',
                'base_price' => 400.00,
                'description' => 'فحص شامل لجميع أعطال السيارة بالكمبيوتر وقراءة الحساسات والـ OBD.',
                'is_active' => true,
                'created_at' => $now
            ],
            [
                'name' => 'طقم تيل فرامل أمامي',
                'base_price' => 850.50,
                'description' => 'تغيير تيل الفرامل الأمامي مع مراجعة الطنابير ومستوى زيت الباكم.',
                'is_active' => true,
                'created_at' => $now
            ],
            [
                'name' => 'تغيير زيت المحرك 10 آلاف كم',
                'base_price' => 1250.00,
                'description' => 'تغيير زيت كامل 10 آلاف مع الفلتر الأصلي ومراجعة سوائل السيارة.',
                'is_active' => true,
                'created_at' => $now
            ],
        ];

        foreach ($servicesData as $service) {
            $serviceIds[] = DB::table('services')->insertGetId($service);
        }

        // 3. إضافة طلبات (Orders) جديدة وربطها بالبيانات الجديدة
        DB::table('orders')->insert([
            [
                'user_id' => $userIds[0], 'service_id' => $serviceIds[0], 'status' => 'completed',
                'total_price' => 600.00, 'created_at' => $now->copy()->subMonth()
            ],
            [
                'user_id' => $userIds[1], 'service_id' => $serviceIds[2], 'status' => 'completed',
                'total_price' => 850.50, 'created_at' => $now->copy()->subDays(3)
            ],
            [
                'user_id' => $userIds[2], 'service_id' => $serviceIds[1], 'status' => 'cancelled',
                'total_price' => 400.00, 'created_at' => $now->copy()->subDays(2)
            ],
            [
                'user_id' => $userIds[0], 'service_id' => $serviceIds[0], 'status' => 'completed',
                'total_price' => 600.00, 'created_at' => $now->copy()->subHours(1)
            ],
            [
                'user_id' => $userIds[1], 'service_id' => $serviceIds[3], 'status' => 'pending',
                'total_price' => 1250.00, 'created_at' => $now->copy()->subHours(4)
            ],
        ]);
    }
}
