
<?php

#Массив с параметрами, которые нужно передать методом POST к API системы
$user=array(
 'USER_LOGIN'=>'bestcleaning770@gmail.com', #Ваш логин (электронная почта)
 'USER_HASH'=>'09a4c0e29bd630d69e3b6273eca405edaa878f7d' #Хэш для доступа к API (смотрите в профиле пользователя)
);
$subdomain='bestcleaning770'; #Наш аккаунт - поддомен

#Формируем ссылку для запроса
$auth_link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
$lead_link='https://'.$subdomain.'.amocrm.ru/api/v2/leads?status=22375147';
$contact_link='https://'.$subdomain.'.amocrm.ru/api/v2/contacts/';
$note_link='https://'.$subdomain.'.amocrm.ru/api/v2/notes';
/* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Вы также
можете
использовать и кроссплатформенную программу cURL, если вы не программируете на PHP. */
	$auth_curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($auth_curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($auth_curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($auth_curl,CURLOPT_URL,$auth_link);
	curl_setopt($auth_curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($auth_curl,CURLOPT_POSTFIELDS,json_encode($user));
	curl_setopt($auth_curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($auth_curl,CURLOPT_HEADER,false);
	curl_setopt($auth_curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($auth_curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 
	curl_setopt($auth_curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($auth_curl,CURLOPT_SSL_VERIFYHOST,false);
	#curl_setopt($auth_curl,CURLOPT_VERBOSE,true);
	$auth=curl_exec($auth_curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$auth_code=curl_getinfo($auth_curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера	
	curl_close($auth_curl); #Завершаем сеанс cURL AUTH	
				
				$lead_curl=curl_init();
				/* Устанавливаем необходимые опции для сеанса cURL */
				curl_setopt($lead_curl,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($lead_curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
				curl_setopt($lead_curl,CURLOPT_URL,$lead_link);
				curl_setopt($lead_curl,CURLOPT_HEADER,false);
				
				curl_setopt($lead_curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
				curl_setopt($lead_curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 
				
				curl_setopt($lead_curl,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($lead_curl,CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($lead_curl,CURLOPT_VERBOSE,true);
				/* Вы также можете передать дополнительный HTTP-заголовок IF-MODIFIED-SINCE, в котором указывается дата в формате D, d M Y
				H:i:s. При
				передаче этого заголовка будут возвращены сделки, изменённые позже этой даты. */
				curl_setopt($lead_curl,CURLOPT_HTTPHEADER,array('IF-MODIFIED-SINCE: Mon, 01 Aug 2013 07:07:23'));
				/* Выполняем запрос к серверу. */
				$lead_out=curl_exec($lead_curl); #Инициируем запрос к API и сохраняем ответ в переменную
				$lead_code=curl_getinfo($lead_curl,CURLINFO_HTTP_CODE);
				curl_close($lead_curl); #CLOSE LEAD 
							$contact_curl=curl_init(); #Сохраняем дескриптор сеанса cURL
							#Устанавливаем необходимые опции для сеанса cURL
							curl_setopt($contact_curl,CURLOPT_RETURNTRANSFER,true);
							curl_setopt($contact_curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
							curl_setopt($contact_curl,CURLOPT_URL,$contact_link);
							curl_setopt($contact_curl,CURLOPT_HEADER,false);
							curl_setopt($contact_curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
							curl_setopt($contact_curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
							curl_setopt($contact_curl,CURLOPT_SSL_VERIFYPEER,0);
							curl_setopt($contact_curl,CURLOPT_SSL_VERIFYHOST,0);
							$contact_out=curl_exec($contact_curl); #Инициируем запрос к API и сохраняем ответ в переменную
							$contact_code=curl_getinfo($contact_curl,CURLINFO_HTTP_CODE);
							curl_close($contact_curl);

/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$auth_code =(int)$auth_code;

$errors=array(
  301=>'Moved permanently',
  400=>'Bad request',
  401=>'Unauthorized',
  403=>'Forbidden',
  404=>'Not found',
  500=>'Internal server error',
  502=>'Bad gateway',
  503=>'Service unavailable'
);
try
{
  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
 if($auth_code!=200 && $auth_code!=204)
    throw new Exception(isset($errors[$auth_code]) ? $errors[$auth_code] : 'Undescribed error',$auth_code);
}
catch(Exception $E)
{
  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
}
/*
 Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 нам придётся перевести ответ в формат, понятный PHP
 */
/* 					$Auth_Response=json_decode($auth,true);
					$Auth_Response=$Auth_Response['response'];

					if(isset($Auth_Response['auth'])) #Флаг авторизации доступен в свойстве "auth"
					 print 'Авторизация прошла успешно';
					 else
					print 'Авторизация не удалась';
 *//*===============================LEAD=======================*/

$lead_code=(int)$lead_code;

$errors=array(
  301=>'Moved permanently',
  400=>'Bad request',
  401=>'Unauthorized',
  403=>'Forbidden',
  404=>'Not found',
  500=>'Internal server error',
  502=>'Bad gateway',
  503=>'Service unavailable'
);
try
{
  /* Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке */
  if($lead_code!=200 && $lead_code!=204) {
    throw new Exception(isset($errors[$lead_code]) ? $errors[$lead_code] : 'Undescribed error',$lead_code);
  }
}
catch(Exception $E)
{
  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
}
/*
 Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 нам придётся перевести ответ в формат, понятный PHP

 */

 $Lead_Response=json_decode($lead_out,true);

 	
 $Lead_Response_Items=$Lead_Response['_embedded']['items']; #(0.1.2.3....10..z)
 for($i = 0; $i < count($Lead_Response_Items); ++$i) {
    $Prn = $Lead_Response_Items[$i]['id'] ;
print_r ("сделка".$Prn."\n");
$c=$c+1;

#=================NOTES==========================================================
$note_data = array (
					  'add' =>
					  array (
						0 =>
						array (
						  'element_id' => $Prn,
						  'element_type' => '2',
						  'text' => 'web test',
						  'note_type' => '4'
						  ),
					  ),
					);
				

					$note_curl=curl_init(); #Сохраняем дескриптор сеанса cURL
					#Устанавливаем необходимые опции для сеанса cURL
					curl_setopt($note_curl,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($note_curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
					curl_setopt($note_curl,CURLOPT_URL,$note_link);
					curl_setopt($note_curl,CURLOPT_CUSTOMREQUEST,'POST');
					curl_setopt($note_curl,CURLOPT_POSTFIELDS,json_encode($note_data));
					curl_setopt($note_curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
					curl_setopt($note_curl,CURLOPT_HEADER,false);
					curl_setopt($note_curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
					curl_setopt($note_curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
					curl_setopt($note_curl,CURLOPT_SSL_VERIFYPEER,0);
					curl_setopt($note_curl,CURLOPT_SSL_VERIFYHOST,0);
					$note_out=curl_exec($note_curl); #Инициируем запрос к API и сохраняем ответ в переменную
					$note_code=curl_getinfo($note_curl,CURLINFO_HTTP_CODE);
					/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
					$note_code=(int)$note_code;
					$errors=array(
					  301=>'Moved permanently',
					  400=>'Bad request',
					  401=>'Unauthorized',
					  403=>'Forbidden',
					  404=>'Not found',
					  500=>'Internal server error',
					  502=>'Bad gateway',
					  503=>'Service unavailable'
					);
					try
					{
					  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
					 if($note_code!=200 && $note_code!=204)
						throw new Exception(isset($errors[$note_code]) ? $errors[$note_code] : 'Undescribed error',$note_code);
					}
					catch(Exception $E)
					{
					  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
					}
 $note_Response=json_decode($note_out,true);
 print_r ($note_Response);
print_r ($note_data);





	}	
	print "всего сделок: ".$c;
 $Lead_Response_Contacts = $Lead_Response_Items['contacts']['id']; #(0.1.2.3....10..z)
 
#print_r($Lead_Response);


#============================================CONTACTS===============================

$contact_code =(int)$contact_code;

$errors=array(
  301=>'Moved permanently',
  400=>'Bad request',
  401=>'Unauthorized',
  403=>'Forbidden',
  404=>'Not found',
  500=>'Internal server error',
  502=>'Bad gateway',
  503=>'Service unavailable'
);
try
{
  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
 if($contact_code!=200 && $contact_code!=204)
    throw new Exception(isset($errors[$contact_code]) ? $errors[$contact_code] : 'Undescribed error',$contact_code);
}
catch(Exception $E)
{
  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
}

$Contact_Response=json_decode($contact_out,true);
 $Contact_Response_Items=$Contact_Response['_embedded']['items'];
#print_r ($Contact_Response_Items);



?>

