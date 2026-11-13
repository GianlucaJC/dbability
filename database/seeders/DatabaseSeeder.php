<?php

namespace Database\Seeders;

use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::query()
            ->where('slug', '!=', Product::STORE_SLUG)
            ->update(['is_active' => false]);

        Product::upsert([
            [
                'title' => 'Percorsi di pregrafismo creativo',
                'slug' => Product::STORE_SLUG,
                'description' => 'Schede operative pensate per allenare la motricità fine, la presa e il gesto grafico con gradualità.',
                'price_cents' => 3500,
                'print_price_cents' => 4500,
                'file_path' => 'pdfs/percorsi-pregrafismo-creativo.pdf',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['slug'], ['title', 'description', 'price_cents', 'print_price_cents', 'file_path', 'is_active', 'updated_at']);
    }
}
