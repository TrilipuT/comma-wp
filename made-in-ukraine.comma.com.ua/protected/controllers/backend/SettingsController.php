<?php
class SettingsController extends BackEndController{ 
 
 	public function init(){

 		$this->activeModule = 'settings';
		$this->moduleName 	= 'Настройки'; 

 		return true;
 	}
	 
	public function actionIndex(){
  
		$post = Yii::app()->request->getPost('Settings');   

		if(isset($post) && count($post) > 0 ){

			foreach($post as $param => $value){ 

				$Settings = Settings::model()->find('parameter = :param', array(':param' => $param));
				if($Settings){

					$Settings->value = $value;
					$Settings->update(array('value'));
				} 
			} 
			 
		}

 
		$settingsItems = Settings::model()->published()->findAll();
		$this->render('index',array( 'settingsItems' => $settingsItems ));
	}
 

	//https://github.com/wanze/Google-Analytics-API-PHP
	//https://code.google.com/apis/console
	public function actionTest(){

 		//notasecret
		Yii::import('application.extensions.google_analytics.GoogleAnalyticsAPI');

		$ga = new GoogleAnalyticsAPI('service');
		$ga->auth->setClientId('801310361775-ojheujnkl5492p7sc5oleth1bd4ppfom.apps.googleusercontent.com'); // From the APIs console
		$ga->auth->setEmail('801310361775-ojheujnkl5492p7sc5oleth1bd4ppfom@developer.gserviceaccount.com'); // From the APIs console
		$ga->auth->setPrivateKey($_SERVER['DOCUMENT_ROOT'].'/17c7c812bd1a1c08f8d941cc5d3e96e540845d7e-privatekey.p12'); // Path to the .p12 file

		$auth = $ga->auth->getAccessToken();  

		// Try to get the AccessToken
		if ($auth['http_code'] == 200) {
		    $accessToken  = $auth['access_token'];
		    $tokenExpires = $auth['expires_in'];
		    $tokenCreated = time();
			
		    $ga->setAccessToken($accessToken); 

			$profiles = $ga->getProfiles();			
		    //$profiles = $ga->getWebProperties();
			 
			 
			$accounts = array();
			foreach ($profiles['items'] as $item) {
			    $id = "ga:{$item['id']}";
			    $name = $item['name'];
			    $accounts[$id] = $name;
			}
			//var_dump($accounts); 
 
			// Set the accessToken and Account-Id
			$ga->setAccessToken($accessToken);
			$ga->setAccountId($id);

			$GoogleAnalytics = GoogleAnalytics::model()->find(array('limit' => 1,
																	'order' => 'date DESC'));

			// Set the default params. For example the start/end dates and max-results
			if($GoogleAnalytics){

				$startDate = $GoogleAnalytics->date; 
				$defaults = array(
				    'start-date' => $startDate,
				    'end-date' 	 => date('Y-m-d'),
				);
			} else {

				$defaults = array(
				    'start-date' => date('Y-m-d', strtotime('-1 month')),
				    'end-date' => date('Y-m-d'),
				); 
			} 
		 
			$ga->setDefaultQueryParams($defaults);

			// Example1: Get visits by date
			$params = array(
			    'metrics' => 'ga:visits',
			    'dimensions' => 'ga:date',
			);
			$visits = $ga->query($params);

			//echo '<pre>'; var_dump($visits); echo '</pre>';


			if(count($visits['rows']) > 0){

				foreach($visits['rows'] as $item){

					$year 	= substr($item[0], 0, 4);
					$months = substr($item[0], 4, 2);
					$day 	= substr($item[0], 6, 2);
  					 
					$date   = $year.'-'.$months.'-'.$day;  

					if($startDate != NULL && $date == $startDate){
						continue;
					}

					$GoogleAnalytics 		= new GoogleAnalytics();
					$GoogleAnalytics->date 	= $date;
					$GoogleAnalytics->visits= $item[1];
					$GoogleAnalytics->save();

				} 
			}

			

			// Example2: Get visits by country
			/*
			$params = array(
			    'metrics' => 'ga:visits',
			    'dimensions' => 'ga:country',
			    'sort' => '-ga:visits',
			    'max-results' => 30,
			    'start-date' => '2013-01-01' //Overwrite this from the defaultQueryParams
			); 
			*/
			//$visitsByCountry = $ga->query($params);

			// Example3: Same data as Example1 but with the built in method:
			//$visits = $ga->getVisitsByDate();

			// Example4: Get visits by Operating Systems and return max. 100 results
			//$visitsByOs = $ga->getVisitsBySystemOs(array('max-results' => 100));

			// Example5: Get referral traffic
			//$referralTraffic = $ga->getRefferralTraffic();
			//echo '<pre>'; var_dump($referralTraffic); echo '</pre>';
			// Example6: Get visits by languages
			//$visitsByLanguages = $ga->getVisitsByLanguages();


		} else {
		   
			var_dump($auth);
		    exit;
		}
 
	 

	 	/*
		$code = $_GET['code'];

		$Eauth = Yii::app()->eauth;
		$Eauth->services = array('google' => array(
				                    'class' => 'GoogleOAuthService',
				                    'client_id' => '801310361775',
				                    'client_secret' => 'ePsL2nC-nExjM7JW3_poSitM',
				                ));



		$authIdentity = $Eauth->getIdentity('google');
        $authIdentity->redirectUrl = Yii::app()->user->returnUrl;  
        $authIdentity->cancelUrl = $this->createAbsoluteUrl('support/default');

        if($authIdentity->authenticate()){

        	unset($_GET['code']);

        	$identity = new ServiceUserIdentity($authIdentity);

        	$token = $authIdentity->returnToken();

        	Yii::import('application.extensions.google_analytics.GoogleAnalyticsAPI');
        	$ga = new GoogleAnalyticsAPI($authIdentity); 
	        $ga->setAccessToken($token);
	        $profiles = $ga->getProfiles();

	        var_dump($profiles);


        }

         
        exit;

       
        //echo '<pre>'; var_dump($authIdentity->returnToken()); echo '</pre>';
        

        $ga = new GoogleAnalyticsAPI($authIdentity); 
        $ga->setAccessToken($token);
        $profiles = $ga->getProfiles();

        var_dump($profiles);
        //$auth = $ga->auth->getAccessToken($code);

        $ga->setAccountId('ga:42946878');

        $defaults = array(
		    'start-date' => date('Y-m-d', strtotime('-1 month')),
		    'end-date' => date('Y-m-d'),
		);

		$ga->setDefaultQueryParams($defaults);

		// Example1: Get visits by date
		$params = array(
		    'metrics' => 'ga:visits',
		    'dimensions' => 'ga:date',
		);
		$visits = $ga->query($params);

        var_dump($visits, $auth, $profiles);


        exit;

		
 
		
		$auth = $ga->auth->getAccessToken($code);

		// Try to get the AccessToken
		if ($auth['http_code'] == 200) {
		    $accessToken = $auth['access_token'];
		    $refreshToken = $auth['refresh_token'];
		    $tokenExpires = $auth['expires_in'];
		    $tokenCreated = time();
		} else {
		    var_dump($url,$auth);// error...
		}

		*/

	}

	 

 
}