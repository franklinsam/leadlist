<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing leads
        DB::table('leads')->truncate();

        $statuses = array_keys(Lead::getStatusOptions());
        $sources = ['Website', 'Referral', 'Google', 'LinkedIn', 'Conference', 'Direct Mail', 'Cold Call'];
        
        // Create 50 sample leads
        for ($i = 1; $i <= 50; $i++) {
            Lead::create([
                'name' => 'Lead ' . $i . ' ' . fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'company' => fake()->company(),
                'source' => $sources[array_rand($sources)],
                'notes' => fake()->paragraph(),
                'status' => $statuses[array_rand($statuses)],
                'value' => fake()->randomFloat(2, 1000, 50000),
                'expected_close_date' => fake()->dateTimeBetween('now', '+6 months'),
                'assigned_to' => fake()->name(),
                'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
} 