<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\Lga;
use Illuminate\Database\Seeder;

class StateLgaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statesData = [
            'Lagos' => ['Ikeja', 'Badagry', 'Ikorodu', 'Lagos Island', 'Epe'],
            'FCT Abuja' => ['Municipal', 'Bwari', 'Gwagwalada', 'Kuje'],
            'Rivers' => ['Port Harcourt', 'Obio-Akpor', 'Bonny', 'Eleme'],
            'Kano' => ['Fagge', 'Dala', 'Gwale', 'Kano Municipal'],
            'Oyo' => ['Ibadan North', 'Ibadan Northeast', 'Ogbomosho North', 'Oyo West'],
            'Kaduna' => ['Kaduna North', 'Kaduna South', 'Zaria', 'Sabon Gari'],
            'Anambra' => ['Awka South', 'Onitsha North', 'Nnewi North', 'Aguata'],
            'Delta' => ['Oshimili South', 'Warri South', 'Ughelli North', 'Sapele'],
            'Enugu' => ['Enugu North', 'Enugu South', 'Nsukka', 'Udi'],
            'Ogun' => ['Abeokuta South', 'Ijebu Ode', 'Sagamu', 'Ota'],
        ];

        foreach ($statesData as $stateName => $lgas) {
            $state = State::create(['name' => $stateName]);

            foreach ($lgas as $lgaName) {
                Lga::create([
                    'state_id' => $state->id,
                    'name' => $lgaName,
                ]);
            }
        }
    }
}
