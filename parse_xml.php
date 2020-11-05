<?php

$request = curl_init();

curl_setopt($request, CURLOPT_URL, "https://dati.comune.roma.it/catalog/dataset/3dff71f7-a693-4726-9f3e-3f751f4824ee/resource/e3442965-a903-4a97-a398-bd446fe21316/download/amm2016_mun14_preferenze_liste.xml");
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($request,CURLOPT_HEADER, false);

$response = curl_exec($request);

$xml = simplexml_load_string($response, 'SimpleXMLElement',LIBXML_NOCDATA);
// pr($xml);
$array = [];
$lista = $xml->{'Voti_Ottenuti'};
foreach($lista->lista as $obj) {
    // pr($obj);
   
    $listaSezioni = [];
    foreach($obj->sez as $sezione) {
        $itemSez = [
            'id'    => (int) $sezione->attributes()->id_sez,
            'voti'  => (int) $sezione->voti_ottenuti
        ];

        $listaSezioni[] = $itemSez; 
    }
    $item = [
        'nomeLista' => (string) $obj->attributes()->id_lista,
        'sezioni'   => $listaSezioni
    ];
    $array[] = $item;

}
$voti_liste = ['voti_liste' => $array];
$json = json_encode($voti_liste,JSON_PRETTY_PRINT);
$fileName =  __DIR__.'/elezioni_2016_municipio_xiv_voti_liste.json';
file_put_contents($fileName,$json); 

echo "fatto";

function pr($mixed,$die = 0) {
    echo '<pre>';
    print_r ($mixed);
    echo '</pre>';

    if( $die ) die();
}