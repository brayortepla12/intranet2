<?php


use Phinx\Seed\AbstractSeed;

class SerAliVar extends AbstractSeed
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
                'Descripcion'    => 'NORMAL',
                'Abrv' => 'N',
            ],
            [
                'Descripcion'    => 'HIPOGRASA',
                'Abrv' => 'HGR',
            ],
            [
                'Descripcion'    => 'HIPOGLUCIDA',
                'Abrv' => 'HGL',
            ],
            [
                'Descripcion'    => 'HIPERPROTEICA',
                'Abrv' => 'HP',
            ],
            [
                'Descripcion'    => 'HIPOCALORICA',
                'Abrv' => 'HIPOC',
            ],
            [
                'Descripcion'    => 'HIPERCALORICA',
                'Abrv' => 'HIPERC',
            ],
            [
                'Descripcion'    => 'RENAL',
                'Abrv' => 'RENAL',
            ],
            [
                'Descripcion'    => 'HIPOSODICA',
                'Abrv' => 'HNA',
            ],
            [
                'Descripcion'    => 'GASTROPROTECTORA',
                'Abrv' => 'GP',
            ],
            [
                'Descripcion'    => 'LIQUIDA',
                'Abrv' => 'LQ',
            ],
            [
                'Descripcion'    => 'LIQUIDOS CLAROS',
                'Abrv' => 'LQX',
            ],
            [
                'Descripcion'    => 'BLANDA',
                'Abrv' => 'BL',
            ],
            [
                'Descripcion'    => 'SEMIBLANDA',
                'Abrv' => 'SBL',
            ],
            [
                'Descripcion'    => 'COMPLEMENTARIA',
                'Abrv' => 'COMP',
            ],
            [
                'Descripcion'    => 'ASTRINGENTE',
                'Abrv' => 'ASTRING',
            ]
        ];

        $variables = $this->table('sa_var');
        $variables->insert($data)
              ->saveData();
    }
}
