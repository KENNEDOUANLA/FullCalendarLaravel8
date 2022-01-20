<?php

namespace App\Http\Controllers;

require_once  base_path('/vendor/autoload.php');


use Illuminate\Http\Request;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
// offline access will give you both an access and refresh token so that
// your app can refresh the access token without user interaction.
// Using "consent" ensures that your application always receives a refresh token.
// If you are not using offline access, you can omit this.

class Calendar extends Controller{

   private $User;
   private $calendarId;

  function __construct(){
        
        session_start();
        $this->User = new Google_Client();
        $this->User->setAuthConfig(storage_path('app/json/code_secret_client.json'));
        $this->User->setAccessType('offline');
        $this->User->setApprovalPrompt("consent");
        $this->User->setScopes(Google_Service_Calendar::CALENDAR);
        $this->User->setPrompt('select_account consent');
        $this->User->setRedirectUri(route('verifier'));
        $this->calendarId = env('CALENDARID');     
  }

  public function index()
  {
	  
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {  
      $this->User->setAccessToken($_SESSION['access_token']);
         return redirect()->route("calendar");
    }else{
        $authUrl=$this->User->createAuthUrl();
        $filtered_url = filter_var($authUrl, FILTER_SANITIZE_URL);
        return redirect($filtered_url);
    }
  }

  public function help()
  {
    if (!isset($_GET['code'])) {
        $authUrl = $this->User->createAuthUrl();
        $filtered_url = filter_var($authUrl, FILTER_SANITIZE_URL);
        return redirect($filtered_url);
    }
    else{
      $this->User->authenticate($_GET['code']);
      $_SESSION['access_token'] = $this->User->getAccessToken();
      return redirect(route("index"));
    }    
  }

  public function CreateTask(Request $request)
  { if($request->ajax()){
      if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
              $this->User->setAccessToken($_SESSION['access_token']);
              $service = new Google_Service_Calendar($this->User);
              
        $event = new Google_Service_Calendar_Event([
          'summary' => $request->title,
          'description' => $request->description,
          'start' => [
            'dateTime' => $request->start,
            'timeZone' => 'Europe/Paris',
          ],
          'end' => [
            'dateTime' => $request->end,
            'timeZone' => 'Europe/Paris',
          ],
          'reminders' => [
            'useDefault' => FALSE
          ],
        ]);
          $results = $service->events->insert($this->calendarId, $event);
      }
      else{
        return redirect(route("index"));
      }
  }
  }


  public function drop(Request $request)
  {
	  if($request->ajax()){
			if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
					$this->User->setAccessToken($_SESSION['access_token']);
					$service = new Google_Service_Calendar($this->User);
					
					$service->events->delete($this->calendarId, $request->id);
			}
			else{
			return redirect(route("index"));
			}
		}
  }

  public function update(Request $request)
  {
	    if($request->ajax()){
			if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
					$this->User->setAccessToken($_SESSION['access_token']);
					$service = new Google_Service_Calendar($this->User);
					$event=	$service->events->get($this->calendarId, $request->id);
					$event->start->dateTime=$request->start;
					$event->end->dateTime=$request->end;
					$service->events->update($this->calendarId,$event->getId(),$event);
			}
			else{
			     return redirect(route("index"));
			}
		}	
		 
  } 

}
