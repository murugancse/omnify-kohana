<?php defined('SYSPATH') or die('No direct script access.');
include_once './libraries/autoload.php';

class Controller_login extends Controller {

	public function action_index()
	{
		$this->auth_login();
	}
	public function auth_login(){
		$client = new Google_Client();
		$client->setAuthConfig('client_secrets.json');
		$client->setApprovalPrompt('force');
		$client->setAccessType("offline");        // offline access
		$client->setIncludeGrantedScopes(true);   // incremental auth
		$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
		$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
		$client->setRedirectUri(url::base().'login');
		
		if (! isset($_GET['code'])) 
        {
			$auth_url = $client->createAuthUrl(); 
			/* echo $auth_url; */
			file_put_contents('client_tokens.json', '');
			$this->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
		}
		else{
			$client->authenticate($_GET['code']);
			$access_token = $client->getAccessToken();
			file_put_contents('client_tokens.json', json_encode($access_token));
			
			 if ($access_token == null) {
		        $auth_url = $client->createAuthUrl();
				$this->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
		     }
			 if ($client->getAccessToken()) {
				$objOAuthService = new Google_Service_Oauth2($client);
				$userData = $objOAuthService->userinfo->get();
				/* print_r($userData); */
				$emailid=$userData->email;
				$this->redirect(url::base().'success/' . $emailid);		
			 }
		}
		
	}

} 