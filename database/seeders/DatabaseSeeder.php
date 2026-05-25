<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            CategorySeeder::class,
            DishSeeder::class,
            PostSeeder::class,
            BannerSeeder::class,
            PageSeeder::class,
            NavigationMenuSeeder::class,
            GalleryImageSeeder::class,
            BranchSeeder::class,
            HaiPhongGalleryImageSeeder::class,
            TestimonialSeeder::class,
            PromotionSeeder::class,
            DanHuongHaiPhongMenuSeeder::class,
            EnglishTranslationSeeder::class,
            MarketingEnglishTranslationSeeder::class,
        ]);
    }
}
