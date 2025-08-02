<?php

namespace App\Console\Commands;

use App\Models\DataConfig;
use App\Models\ElectricityConfig;
use App\Models\TvConfig;
use Illuminate\Console\Command;

class GenerateMCDPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'samji:mcd {--command= : <tv|data|electricity> command to execute}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate VTpass plans';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        switch ($this->option('command')) {
            case 'tv':
                $this->tvPlans();
                break;

            case 'data':
                $this->dataPlans();
                break;

            case 'electricity':
                $this->electricityPlans();
                break;

            default:
                $this->error("Invalid Option !!");
                break;
        }
    }


    private function tvPlans()
    {
        $this->info("Fetching tv plans");

        TvConfig::where("company_id", 0)->delete();

        $inters = ['DSTV', 'GOTV', 'STARTIMES', 'SHOWMAX'];

        foreach ($inters as $inte) {

            $this->info("Fetching " . $inte . " plans");

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('MCD_URL') . "/tv/" . $inte,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . env('SERVER6_AUTH'),
                    'Content-Type: application/json'
                ),
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($curl);

            echo $response;

            curl_close($curl);

            $rep = json_decode($response, true);

            foreach ($rep['data'] as $plans) {
                $this->info("Inserting record for " . $plans['name']);

                try {
                    TvConfig::create([
                        'desc' => $plans['name'],
                        'identifier' => $plans['coded'],
                        'code' => $plans['coded'],
                        'price' => $plans['price'],
                        'provider' => $inte,
                        'company_id' => 0,
                        'default_id' => 0,
                        'status' => 1
                    ]);
                }catch (\Exception $e){
                    echo $e;
                }
            }
        }

    }

    private function dataPlans()
    {
        $this->info("Fetching data plans");

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MCD_URL') . "/data/all",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . env('SERVER6_AUTH'),
                'Content-Type: application/json'
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        echo $response;

        curl_close($curl);

        $rep = json_decode($response, true);

        foreach ($rep['data'] as $plans) {

            try {
                DataConfig::create([
                    'desc' => $plans['name'],
                    'network' => $plans['network'],
                    'code' => $plans['coded'],
                    'identifier' => $plans['coded'],
                    'price' => $plans['price'],
                    'default_id' => 0,
                    'company_id' => 0,
                    'status' => 1,
                ]);
            }catch (\Exception $e){
                echo $e;
            }
        }
    }

    private function electricityPlans()
    {
        $this->info("Add electricity");

        ElectricityConfig::create([
            'desc' => 'IKEDC',
            'code' => 'ikeja-electric',
            'identifier' => 'ikeja-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'EKEDC',
            'code' => 'eko-electric',
            'identifier' => 'eko-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'KEDCO',
            'code' => 'kano-electric',
            'identifier' => 'kano-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'PHED',
            'code' => 'portharcourt-electric',
            'identifier' => 'portharcourt-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'JED',
            'code' => 'jos-electric',
            'identifier' => 'jos-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'IBEDC',
            'code' => 'ibadan-electric',
            'identifier' => 'ibadan-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'KAEDCO',
            'code' => 'kaduna-electric',
            'identifier' => 'kaduna-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

        ElectricityConfig::create([
            'desc' => 'AEDC',
            'code' => 'abuja-electric',
            'identifier' => 'abuja-electric',
            'discount' => '0.5%',
            'company_id' => 0,
            'default_id' => 0,
            'status' => 1
        ]);

    }
}
