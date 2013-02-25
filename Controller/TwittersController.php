<?php
class TwittersController extends AppController{

	public $Users;
	/**
	* Init
	*/
	public function twitterInit(){
		require APP . 'Plugin' . DS . 'Twitter' . DS . 'Vendor' . DS . 'Twitter'.DS.'twitteroauth.php';

		$cKey = Configure::read('Plugin.Twitter.twitter.consumerKey');
		$cSecret = Configure::read('Plugin.Twitter.twitter.consumerSecret');
		$callBackURL = Configure::read('Plugin.Twitter.twitter.callBackURL');

		$connection = new TwitterOAuth($cKey,$cSecret);

		$request_token = $connection->getRequestToken($callBackURL);


		$this->Session->write('oauth_token',$request_token['oauth_token']);
		$this->Session->write('oauth_token_secret',$request_token['oauth_token_secret']);

		if($connection->http_code){
			$url = $connection->getAuthorizeURL($this->Session->read('oauth_token'));
			$this->redirect($url);
		}else{
			echo 'Impossible de se connecter.Merci de reéssayer ultérieurement';
			die();
		}
	}

	public function callBack(){
		require APP . 'Plugin' . DS . 'Twitter' . DS . 'Vendor' . DS . 'Twitter'.DS.'twitteroauth.php';
		$this->Users = ClassRegistry::init('User');

		if($this->request->is('post')){	
			$this->Users->set($this->request->data('callBack'));
			$this->saveEmail($this->Users,$this->request->data);
		}


		$cKey = Configure::read('Plugin.Twitter.twitter.consumerKey');
		$cSecret = Configure::read('Plugin.Twitter.twitter.consumerSecret');
		$isLogged = false;

		if(isset($_GET['oauth_token']) && $this->Session->read('oauth_token') === $_GET['oauth_token']){
			$connection = new TwitterOAuth($cKey , $cSecret, $this->Session->read('oauth_token'), $this->Session->read('oauth_token_secret'));
			$access_token = $connection->getAccessToken($_GET['oauth_verifier']);

			$this->Session->write('access_token',$access_token);
			$this->Session->delete('oauth_token');
			$this->Session->delete('oauth_token_secret');

			if(200 == $connection->http_code){
				$infos = $connection->get('account/verify_credentials');
				$isLogged = true;
			}else{
				$isLogged = false;
			}
		}else{
				$isLogged = false;
		}

		if($isLogged){
			if(!empty($infos->id)){
				$u = $this->Users->find('first',array(
					'conditions'=>array('twitter_id'=>$infos->id)
				));
				$user = $u;
				if(empty($user)){

					$d['User'] = array(
						'username'=>$infos->screen_name,
						'role'=>'users',
						'twitter_id'=>$infos->id,
						'activate'=>0
					);
					$this->Users->saveField('username',$infos->screen_name);
					$this->Users->saveField('role','users');
					$this->Users->saveField('twitter_id',$infos->id);
					$this->Users->saveField('activate',0);
					$this->set($d);
				}else{
					$this->Auth->login($user);
					$this->redirect('/');
				}
			}else{
				$this->redirect(array('controller'=>'users','action'=>'login','Plugin'=>false));
			}
		}else{
			$this->redirect(array('controller'=>'users','action'=>'login','Plugin'=>false));
		}
	}


	/**
	 * Save email of new member and active his count
	 * @param  [type] $datas : the received data from the form
	 */
	public function saveEmail($u,$datas){
		$datas = $datas['callBack'];
		if($u->validates()){
			$user = $u->find('first',array('conditions'=>array('twitter_id'=>$datas['twitter_id'])));
			$u->id = $user['User']['id'];
			$u->saveField('email',$datas['email']);
			$u->saveField('activate',1);
			$this->redirect('/');
		}else{
			$this->Session->setFlash($u->validationErrors['email'][0]);
		}
	}

}