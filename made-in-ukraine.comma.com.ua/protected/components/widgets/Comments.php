<?php
class Comments extends CWidget {
 
	public $returnUrl;
	public $type;
	public $data_id;

    public $countComments;

    public  $gray,
            $white;

	public function run() { 

		$Users = UsersAuth::isLogin();      

		$GetComments = new GetComments($this->type);
        $GetComments->withType($this->data_id);
        $GetComments->setActive(1);

        $commentsItems = $GetComments->getCommentsRecursive();  
        //echo '<pre>';  var_dump($this->type);  echo '</pre>';

        $this->render('wcomments', array('returnUrl' 	 => $this->returnUrl, 
        								 'Users'     	 => $Users,
        								 'data_id'   	 => $this->data_id,
        								 'type' 	 	 => $this->type,
        								 'commentsItems' => $commentsItems,
                                         'gray'          => $this->gray,
                                         'white'         => $this->white));
    }

} 