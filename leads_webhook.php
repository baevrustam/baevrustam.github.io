
<?php

#������ � �����������, ������� ����� �������� ������� POST � API �������
$user=array(
 'USER_LOGIN'=>'bestcleaning770@gmail.com', #��� ����� (����������� �����)
 'USER_HASH'=>'09a4c0e29bd630d69e3b6273eca405edaa878f7d' #��� ��� ������� � API (�������� � ������� ������������)
);
$subdomain='bestcleaning770'; #��� ������� - ��������

#��������� ������ ��� �������
$auth_link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
$lead_link='https://'.$subdomain.'.amocrm.ru/api/v2/leads?status=22375147';
$contact_link='https://'.$subdomain.'.amocrm.ru/api/v2/contacts/';
$note_link='https://'.$subdomain.'.amocrm.ru/api/v2/notes';
/* ��� ���������� ������������ ������ � �������. ������������� ����������� cURL (������������ � ������� PHP). �� �����
������
������������ � ������������������ ��������� cURL, ���� �� �� �������������� �� PHP. */
	$auth_curl=curl_init(); #��������� ���������� ������ cURL
#������������� ����������� ����� ��� ������ cURL
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
	$auth=curl_exec($auth_curl); #���������� ������ � API � ��������� ����� � ����������
	$auth_code=curl_getinfo($auth_curl,CURLINFO_HTTP_CODE); #������� HTTP-��� ������ �������	
	curl_close($auth_curl); #��������� ����� cURL AUTH	
				
				$lead_curl=curl_init();
				/* ������������� ����������� ����� ��� ������ cURL */
				curl_setopt($lead_curl,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($lead_curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
				curl_setopt($lead_curl,CURLOPT_URL,$lead_link);
				curl_setopt($lead_curl,CURLOPT_HEADER,false);
				
				curl_setopt($lead_curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
				curl_setopt($lead_curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 
				
				curl_setopt($lead_curl,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($lead_curl,CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($lead_curl,CURLOPT_VERBOSE,true);
				/* �� ����� ������ �������� �������������� HTTP-��������� IF-MODIFIED-SINCE, � ������� ����������� ���� � ������� D, d M Y
				H:i:s. ���
				�������� ����� ��������� ����� ���������� ������, ��������� ����� ���� ����. */
				curl_setopt($lead_curl,CURLOPT_HTTPHEADER,array('IF-MODIFIED-SINCE: Mon, 01 Aug 2013 07:07:23'));
				/* ��������� ������ � �������. */
				$lead_out=curl_exec($lead_curl); #���������� ������ � API � ��������� ����� � ����������
				$lead_code=curl_getinfo($lead_curl,CURLINFO_HTTP_CODE);
				curl_close($lead_curl); #CLOSE LEAD 
							$contact_curl=curl_init(); #��������� ���������� ������ cURL
							#������������� ����������� ����� ��� ������ cURL
							curl_setopt($contact_curl,CURLOPT_RETURNTRANSFER,true);
							curl_setopt($contact_curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
							curl_setopt($contact_curl,CURLOPT_URL,$contact_link);
							curl_setopt($contact_curl,CURLOPT_HEADER,false);
							curl_setopt($contact_curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
							curl_setopt($contact_curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
							curl_setopt($contact_curl,CURLOPT_SSL_VERIFYPEER,0);
							curl_setopt($contact_curl,CURLOPT_SSL_VERIFYHOST,0);
							$contact_out=curl_exec($contact_curl); #���������� ������ � API � ��������� ����� � ����������
							$contact_code=curl_getinfo($contact_curl,CURLINFO_HTTP_CODE);
							curl_close($contact_curl);

/* ������ �� ����� ���������� �����, ���������� �� �������. ��� ������. �� ������ ���������� ������ ����� ��������. */
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
  #���� ��� ������ �� ����� 200 ��� 204 - ���������� ��������� �� ������
 if($auth_code!=200 && $auth_code!=204)
    throw new Exception(isset($errors[$auth_code]) ? $errors[$auth_code] : 'Undescribed error',$auth_code);
}
catch(Exception $E)
{
  die('������: '.$E->getMessage().PHP_EOL.'��� ������: '.$E->getCode());
}
/*
 ������ �������� � ������� JSON, �������, ��� ��������� �������� ������,
 ��� ������� ��������� ����� � ������, �������� PHP
 */
/* 					$Auth_Response=json_decode($auth,true);
					$Auth_Response=$Auth_Response['response'];

					if(isset($Auth_Response['auth'])) #���� ����������� �������� � �������� "auth"
					 print '����������� ������ �������';
					 else
					print '����������� �� �������';
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
  /* ���� ��� ������ �� ����� 200 ��� 204 - ���������� ��������� �� ������ */
  if($lead_code!=200 && $lead_code!=204) {
    throw new Exception(isset($errors[$lead_code]) ? $errors[$lead_code] : 'Undescribed error',$lead_code);
  }
}
catch(Exception $E)
{
  die('������: '.$E->getMessage().PHP_EOL.'��� ������: '.$E->getCode());
}
/*
 ������ �������� � ������� JSON, �������, ��� ��������� �������� ������,
 ��� ������� ��������� ����� � ������, �������� PHP

 */

 $Lead_Response=json_decode($lead_out,true);

 	
 $Lead_Response_Items=$Lead_Response['_embedded']['items']; #(0.1.2.3....10..z)
 for($i = 0; $i < count($Lead_Response_Items); ++$i) {
    $Prn = $Lead_Response_Items[$i]['id'] ;
print_r ("������".$Prn."\n");
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
				

					$note_curl=curl_init(); #��������� ���������� ������ cURL
					#������������� ����������� ����� ��� ������ cURL
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
					$note_out=curl_exec($note_curl); #���������� ������ � API � ��������� ����� � ����������
					$note_code=curl_getinfo($note_curl,CURLINFO_HTTP_CODE);
					/* ������ �� ����� ���������� �����, ���������� �� �������. ��� ������. �� ������ ���������� ������ ����� ��������. */
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
					  #���� ��� ������ �� ����� 200 ��� 204 - ���������� ��������� �� ������
					 if($note_code!=200 && $note_code!=204)
						throw new Exception(isset($errors[$note_code]) ? $errors[$note_code] : 'Undescribed error',$note_code);
					}
					catch(Exception $E)
					{
					  die('������: '.$E->getMessage().PHP_EOL.'��� ������: '.$E->getCode());
					}
 $note_Response=json_decode($note_out,true);
 print_r ($note_Response);
print_r ($note_data);





	}	
	print "����� ������: ".$c;
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
  #���� ��� ������ �� ����� 200 ��� 204 - ���������� ��������� �� ������
 if($contact_code!=200 && $contact_code!=204)
    throw new Exception(isset($errors[$contact_code]) ? $errors[$contact_code] : 'Undescribed error',$contact_code);
}
catch(Exception $E)
{
  die('������: '.$E->getMessage().PHP_EOL.'��� ������: '.$E->getCode());
}

$Contact_Response=json_decode($contact_out,true);
 $Contact_Response_Items=$Contact_Response['_embedded']['items'];
#print_r ($Contact_Response_Items);



?>

