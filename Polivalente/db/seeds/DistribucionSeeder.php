<?php


use Phinx\Seed\AbstractSeed;

class DistribucionSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'Nombre'    => 'Desayuno',
                'Abrv' => 'D',
                'HoraLimite' => '23:59:59',
                'HasHoraLimite' => 1,
                'Orden' => 0
            ],
            [
                'Nombre'    => 'Media Mañana',
                'Abrv' => 'MM',
                'HoraLimite' => null,
                'HasHoraLimite' => 0,
                'Orden' => 1
            ],
            [
                'Nombre'    => 'Almuerzo',
                'Abrv' => 'A',
                'HoraLimite' => '10:30',
                'HasHoraLimite' => 1,
                'Orden' => 2
            ],
            [
                'Nombre'    => 'Media Tarde',
                'Abrv' => 'MT',
                'HoraLimite' => null,
                'HasHoraLimite' => 0,
                'Orden' => 3
            ],
            [
                'Nombre'    => 'Cena',
                'Abrv' => 'C',
                'HoraLimite' => '15:00',
                'HasHoraLimite' => 1,
                'Orden' => 4
            ],
            [
                'Nombre'    => 'Media Noche',
                'Abrv' => 'MN',
                'HoraLimite' => null,
                'HasHoraLimite' => 0,
                'Orden' => 5
            ]
        ];
        $variables = $this->table('sa_distribucion');
        $variables->insert($data)
              ->saveData();
    }
}
