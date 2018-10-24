<?php defined('SYSPATH') or die('No direct script access.');
include_once './libraries/autoload.php';

class Controller_calendar extends Controller {

	public function action_index()
	{
		$client = new Google_Client();
		$client->setAuthConfig('client_secrets.json');
		$client->setApprovalPrompt('force');
		$client->setAccessType("offline");        // offline access
		$client->setIncludeGrantedScopes(true);   // incremental auth
		$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
		$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
		$client->setRedirectUri(url::base().'login');
		
		$tokenPath='client_tokens.json';
		$accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

	    if ($accessToken == null ) {
            $auth_url = $client->createAuthUrl();
			$this->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
	    }
		
		$service = new Google_Service_Calendar($client);
		$events = $service->events->listEvents('primary');
		
		$google_oauth = new Google_Service_Oauth2($client);
		$emailid = $google_oauth->userinfo->get()->email;

		while(!empty($events)) {
		  foreach ($events->getItems() as $event) {
			  /* print_r($event); */
				$eventid=$event->id;
				$summary=$event->getSummary();
				$link=$event->htmlLink;
				$updated=$event->updated;
				
				 /* $db=new Database; */
				/*  $user = ORM::factory('calendar')
					->find_all(); */
					
				/* $google_client =  Model::factory('Calendar'); */

				$query = DB::select()->from('calendar')->where('google_id', '=', $event->id);
				$results = $query->execute();
				if(count($results) == 0){
					$insquery = DB::insert('calendar', array('gmail', 'google_id', 'summary', 'link','up_datetime'))->values(array($emailid,$eventid,$summary
					,$link ,$updated));
					$result = $insquery->execute();	
				}
				
		  }
		  $pageToken = $events->getNextPageToken();
		  if ($pageToken) {
			$optParams = array('pageToken' => $pageToken);
			$events = $service->events->listEvents('primary', $optParams);
		  } else {
			break;
		  }
		}
	
	
	$queryone = DB::select()->from('calendar')->where('gmail', '=', $emailid);

	$alldata = $queryone->execute();

	$this->response->body(View::factory('calendar')->bind('data', $alldata));
	
	}
} 