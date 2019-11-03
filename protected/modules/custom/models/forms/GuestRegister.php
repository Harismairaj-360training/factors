<?php
namespace humhub\modules\custom\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Guest Registration Form is the model behind the Guest Registration form.
 */
class GuestRegister extends Model
{
    /**
     * @var string user's firstname
     */
    public $firstname;
    /**
     * @var string user's lastname
     */
    public $lastname;
    /**
     * @var string user's email
     */
    public $email;
    /**
     * @var string user's phonenumber
     */
    public $phonenumber;
    /**
     * @var string user's password
     */
    public $password;
    /**
     * @var string user's repeatpassword
     */
    public $repeatpassword;
    public $agreementCheckbox;
    public $usergroup;
    public $username;

    public function init()
    {
       parent::init();
    }
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['firstname','lastname','email','phonenumber','password','repeatpassword'],'required','message' => 'This is a required field.'],
            [['firstname','lastname','email','phonenumber','password','repeatpassword'],'trim'],
            [['email'], 'email', 'message' => 'Please enter a valid email address (Ex: johndoe@domain.com).'],
            [['password', 'repeatpassword'], 'string', 'min' => 7, 'message' => 'Minimum length of this field must be equal or greater than 7 symbols. Leading and trailing spaces will be ignored.'],
            [['repeatpassword'], 'compare', 'compareAttribute' => 'password', 'message' => 'Please enter the same value again.'],
            ['agreementCheckbox', 'boolean']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array(
            'guestcomment'=>'',
            'firstname'=>'',
            'lastname'=>'',
            'email'=>'',
            'phonenumber'=>'',
            'password'=>'',
            'repeatpassword'=>'',
            'agreementCheckbox'=>''
        );
    }

    public function registerInHumhub()
    {
      $endPoint = Url::base(true).'/api/quickstart/experts?access_token=IRw22b9xEW6zvJ6w4EmckjBY';
      $headers = array(
          'Accept: application/json',
          'Content-type: application/json'
      );
      $data = [
        	"User"=>[
        		"username"=>$this->email,
        		"email"=>$this->email
        	],
        	"Password"=>[
        		"newPassword"=>$this->firstname."!@#",
        		"newPasswordConfirm"=>$this->firstname."!@#"
        	],
        	"Profile"=>[
        		"firstname"=>$this->firstname,
        		"lastname"=>$this->lastname,
        		"image"=>""
        	],
        	"Spaces"=>[
        		"addIn"=>[],
        		"removeFrom"=>[]
        	],
        	"UserGroup"=>"learners"
      ];
      if(!empty($this->username))
      {
        $data['User']['username'] = $this->username;
      }
      if(!empty($this->usergroup))
      {
        $data['UserGroup'] = $this->usergroup;
      }

      return $this->curlRequest($endPoint,$headers,$data);
    }
    private function curlRequest($endPoint,$headers,$data)
    {
      $params = json_encode($data);
      $ch = curl_init($endPoint);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $parsed = json_decode(curl_exec($ch));
      curl_close($ch);
      return $parsed;
    }
}
