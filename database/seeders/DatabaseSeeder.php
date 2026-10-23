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
        Product::upsert([
            [
                'title' => 'Giochi di movimento e coordinazione',
                'slug' => 'giochi-movimento-coordinazione',
                'description' => 'Attività guidate per accompagnare il bambino nello sviluppo della coordinazione e della consapevolezza corporea.',
                'price_cents' => 3000,
                'file_path' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Percorsi di pregrafismo creativo',
                'slug' => 'percorsi-pregrafismo-creativo',
                'description' => 'Schede operative pensate per allenare la motricità fine, la presa e il gesto grafico con gradualità.',
                'price_cents' => 3500,
                'file_path' => 'pdfs/percorsi-pregrafismo-creativo.pdf',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Routine per l’autonomia quotidiana',
                'slug' => 'routine-autonomia-quotidiana',
                'description' => 'Strumenti pratici per trasformare le routine di ogni giorno in occasioni di crescita e autonomia.',
                'price_cents' => 4000,
                'file_path' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['slug'], ['title', 'description', 'price_cents', 'file_path', 'is_active', 'updated_at']);
    }
}
