<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = [
            [
                'rm_no' => '3245124',
                'registration_no' => '',
                'name' => 'Anang Julyanto',
                'birthday' => '1995-01-01',
                'email' => 'anang123julian@gmail.com',
                'phone' => '+6281389771866'
            ],
            [
                'rm_no' => '5464328',
                'registration_no' => '',
                'name' => 'Dyan Elyosha',
                'birthday' => '1995-01-01',
                'email' => 'dian@gmail.com',
                'phone' => '+62549646541'
            ],
            [
                'rm_no' => '9873453',
                'registration_no' => '',
                'name' => 'Dewi Lestari',
                'birthday' => '1992-01-01',
                'email' => 'dewi@gmail.com',
                'phone' => '+626435169168'
            ],
        ];
        foreach ($patients as $key => $value) {
            $patient = Patient::create($value);
        }
    }
}
