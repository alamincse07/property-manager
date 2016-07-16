<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Real Estate CMS Pro
 *
 * Author: Ramazan APAYDIN
 *         apaydinweb@gmail.com
 *
 * Website: http://ramazanapaydin.com
 *
 * Created:  04.15.2013
*/
function countryList($activeCountry = NULL) {
	$country = array(
			"-" => "Select Country",
			"United States" => "United States",
			//"Turkey" => "Turkey",
	);
	if ($activeCountry != NULL) {
		return $country[$activeCountry];
	}
	return $country;
}

function stateList($activeState = NULL, $activeCountry = NULL) {
	$states['United States'] = array(
			"-" => "Select State/Province",
			'Alabama' => 'Alabama',
			'Alaska' => 'Alaska',
			'Arizona' => 'Arizona',
			'Arkansas' => 'Arkansas',
			'California' => 'California',
			'Colorado' => 'Colorado',
			'Connecticut' => 'Connecticut',
			'Delaware' => 'Delaware',
			'District Of Columbia' => 'District Of Columbia',
			'Florida' => 'Florida',
			'Georgia' => 'Georgia',
			'Hawaii' => 'Hawaii',
			'Idaho' => 'Idaho',
			'Illinois' => 'Illinois',
			'Indiana' => 'Indiana',
			'Iowa' => 'Iowa',
			'Kansas' => 'Kansas',
			'Kentucky' => 'Kentucky',
			'Louisiana' => 'Louisiana',
			'Maine' => 'Maine',
			'Maryland' => 'Maryland',
			'Massachusetts' => 'Massachusetts',
			'Michigan' => 'Michigan',
			'Minnesota' => 'Minnesota',
			'Mississippi' => 'Mississippi',
			'Missouri' => 'Missouri',
			'Montana' => 'Montana',
			'Nebraska' => 'Nebraska',
			'Nevada' => 'Nevada',
			'New Hampshire' => 'New Hampshire',
			'New Jersey' => 'New Jersey',
			'New Mexico' => 'New Mexico',
			'New York' => 'New York',
			'North Carolina' => 'North Carolina',
			'North Dakota' => 'North Dakota',
			'Ohio' => 'Ohio',
			'Oklahoma' => 'Oklahoma',
			'Oregon' => 'Oregon',
			'Pennsylvania' => 'Pennsylvania',
			'Rhode Island' => 'Rhode Island',
			'South Carolina' => 'South Carolina',
			'South Dakota' => 'South Dakota',
			'Tennessee' => 'Tennessee',
			'Texas' => 'Texas',
			'Utah' => 'Utah',
			'Vermont' => 'Vermont',
			'Virginia' => 'Virginia',
			'Washington' => 'Washington',
			'West Virginia' => 'West Virginia',
			'Wisconsin' => 'Wisconsin',
			'Wyoming' => 'Wyoming'
	);

	$states['Turkey'] = array(
			"-" => "Select State/Province",
			'Adana' => 'Adana',
			'Adıyaman' => 'Adıyaman',
			'Afyonkarahisar' => 'Afyonkarahisar',
			'Ağrı' => 'Ağrı',
			'Aksaray' => 'Aksaray',
			'Amasya' => 'Amasya',
			'Ankara' => 'Ankara',
			'Antalya' => 'Antalya',
			'Ardahan' => 'Ardahan',
			'Artvin' => 'Artvin',
			'Aydın' => 'Aydın',
			'Balıkesir' => 'Balıkesir',
			'Bartın' => 'Bartın',
			'Batman' => 'Batman',
			'Bayburt' => 'Bayburt',
			'Bilecik' => 'Bilecik',
			'Bingöl' => 'Bingöl',
			'Bitlis' => 'Bitlis',
			'Bolu' => 'Bolu',
			'Burdur' => 'Burdur',
			'Bursa' => 'Bursa',
			'Çanakkale' => 'Çanakkale',
			'Çankırı' => 'Çankırı',
			'Çorum' => 'Çorum',
			'Denizli' => 'Denizli',
			'Diyarbakır' => 'Diyarbakır',
			'Düzce' => 'Düzce',
			'Edirne' => 'Edirne',
			'Elazığ' => 'Elazığ',
			'Erzincan' => 'Erzincan',
			'Erzurum' => 'Erzurum',
			'Eskişehir' => 'Eskişehir',
			'Gaziantep' => 'Gaziantep',
			'Giresun' => 'Giresun',
			'Gümüşhane' => 'Gümüşhane',
			'Hakkari' => 'Hakkari',
			'Hatay' => 'Hatay',
			'Iğdır' => 'Iğdır',
			'Isparta' => 'Isparta',
			'İstanbul' => 'İstanbul',
			'İzmir' => 'İzmir',
			'Kahramanmaraş' => 'Kahramanmaraş',
			'Karabük' => 'Karabük',
			'Karaman' => 'Karaman',
			'Kars' => 'Kars',
			'Kastamonu' => 'Kastamonu',
			'Kayseri' => 'Kayseri',
			'Kırıkkale' => 'Kırıkkale',
			'Kırklareli' => 'Kırklareli',
			'Kırşehir' => 'Kırşehir',
			'Kilis' => 'Kilis',
			'Kocaeli' => 'Kocaeli',
			'Konya' => 'Konya',
			'Kütahya' => 'Kütahya',
			'Malatya' => 'Malatya',
			'Manisa' => 'Manisa',
			'Mardin' => 'Mardin',
			'Mersin' => 'Mersin',
			'Muğla' => 'Muğla',
			'Muş' => 'Muş',
			'Nevşehir' => 'Nevşehir',
			'Niğde' => 'Niğde',
			'Ordu' => 'Ordu',
			'Osmaniye' => 'Osmaniye',
			'Rize' => 'Rize',
			'Sakarya' => 'Sakarya',
			'Samsun' => 'Samsun',
			'Siirt' => 'Siirt',
			'Sinop' => 'Sinop',
			'Sivas' => 'Sivas',
			'Şanlı urfa' => 'Şanlı urfa',
			'Şırnak' => 'Şırnak',
			'Tekirdağ' => 'Tekirdağ',
			'Tokat' => 'Tokat',
			'Trabzon' => 'Trabzon',
			'Tunceli' => 'Tunceli',
			'Uşak' => 'Uşak',
			'Van' => 'Van',
			'Yalova' => 'Yalova',
			'Yozgat' => 'Yozgat',
			'Zonguldak' => 'Zonguldak'
	);

	if (($activeState != NULL) and ($activeCountry != NULL)) {
		return $states[$activeCountry][$activeState];
	}
	if ($activeCountry != NULL) {
		return json_encode($states[$activeCountry]);
	}

	return $states;
}

?>