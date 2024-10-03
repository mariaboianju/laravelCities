<?php



/*ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);
$url='http://api.geonames.org/searchJSON?fcode=BUSTN&country=' . $_REQUEST['country'] . '&username=mariaboianju';

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);

$result=curl_exec($ch);

curl_close($ch);

$decode = json_decode($result,true);	

$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
$output['data'] = $decode["geonames"];

header('Content-Type: application/json; charset=UTF-8');

echo json_encode($output); */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseCities extends Command
{
    //
    // Название команды
    protected $signature = 'parse:cities';

    // Описание команды
    protected $description = 'Парсинг городов России и их загрузка в базу данных';

    // Метод, который выполняется при запуске команды
    public function handle()
    {
        // URL для API
        $url = 'https://api.hh.ru/areas';

        // Получаем данные через API
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        // Ищем Россию (id: 113) и получаем города
        foreach ($data as $country) {
            if ($country['id'] == '113') {
                $regions = $country['areas'];

                foreach ($regions as $region) {
                    foreach ($region['areas'] as $city) {
                        // Вставляем город в базу данных
                        DB::table('cities')->insert([
                            'name' => $city['name'],
                            'region_id' => $region['id'],
                            'city_id' => $city['id']
                        ]);
                        $this->info("Город {$city['name']} добавлен в базу данных.");
                    }
                }
            }
        }

        $this->info('Парсинг городов завершен.');
    }
}
