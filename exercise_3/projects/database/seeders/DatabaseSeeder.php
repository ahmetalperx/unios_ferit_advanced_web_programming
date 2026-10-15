<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {

    // ---------------------------------------------------------------------------------------------- //

    public function run() : void {

        // 1. Official Course Teaching Staff & Student Users

        $student = User::updateOrCreate(['email' => 'alper@ferit.hr'], [
            'name' => 'Ahmet Alper Erdoğan',
            'password' => Hash::make('password123')
        ]);

        $prof = User::updateOrCreate(['email' => 'ivica.lukic@ferit.hr'], [
            'name' => 'prof. dr. sc. Ivica Lukić',
            'password' => Hash::make('password123')
        ]);

        $asst1 = User::updateOrCreate(['email' => 'dina.sabanovic@ferit.hr'], [
            'name' => 'Dina Šabanović',
            'password' => Hash::make('password123')
        ]);

        $asst2 = User::updateOrCreate(['email' => 'tea.krcmar@ferit.hr'], [
            'name' => 'Tea Krčmar, univ. mag. ing. comp.',
            'password' => Hash::make('password123')
        ]);

        // ------------------------------------------------------------------------------------------ //

        // 2. Project 1: Managed by Ahmet Alper Erdoğan (Student)

        $project1 = Project::updateOrCreate(
            ['name' => 'FERIT Student Portal & Project Platform'],
            [
                'description' => 'Modern collaborative web platform for FERIT Osijek students and faculty with multi-language support (EN, PL, TR).',
                'price' => 15000.00,
                'done_jobs' => 'Architecture planned, MVC controllers implemented, OpenSSL vaults and dynamic localization integrated.',
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-31',
                'manager_id' => $student -> id
            ]
        );

        $project1 -> members() -> sync([$prof -> id, $asst1 -> id, $asst2 -> id]);

        // ------------------------------------------------------------------------------------------ //

        // 3. Project 2: Managed by prof. dr. sc. Ivica Lukić (Course Professor)

        $project2 = Project::updateOrCreate(
            ['name' => 'Napredno Web Programiranje (Course Management)'],
            [
                'description' => 'Official FERIT Osijek laboratory curriculum system, consultative lecture records, and student assessment platform.',
                'price' => 8500.00,
                'done_jobs' => 'Laboratory exercises 1, 2, and 3 prepared, evaluation criteria published.',
                'start_date' => '2026-10-05',
                'end_date' => '2027-02-28',
                'manager_id' => $prof -> id
            ]
        );

        $project2 -> members() -> sync([$student -> id, $asst1 -> id, $asst2 -> id]);

        // ------------------------------------------------------------------------------------------ //

        // 4. Project 3: Managed by Dina Šabanović (Course Assistant)

        $project3 = Project::updateOrCreate(
            ['name' => 'Automotive Marketplace & Microservices Prototype'],
            [
                'description' => 'Scalable vehicle trading platform prototype developed with Laravel and MySQL architecture.',
                'price' => 22000.00,
                'done_jobs' => 'Database schema design, authentication middleware, initial API endpoints.',
                'start_date' => '2026-11-01',
                'end_date' => '2027-01-31',
                'manager_id' => $asst1 -> id
            ]
        );

        $project3 -> members() -> sync([$student -> id, $asst2 -> id]);

    }

    // ---------------------------------------------------------------------------------------------- //

}
