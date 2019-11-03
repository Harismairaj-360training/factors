<?php
namespace humhub\modules\custom\models\forms;

use Yii;
use yii\base\Model;

/**
 * Guest LoginForm is the model behind the Guest login form.
 */
class GuestLogin extends Model
{
    /**
     * @var string user's email
     */
    public $email;
    /**
     * @var string user's password
     */
    public $password;
    public $agreementCheckbox;

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
            [['email','password'],'required','message' => 'This is a required field.'],
            [['email','password'],'trim'],
            [['email'], 'email', 'message' => 'Please enter a valid email address (Ex: johndoe@domain.com).'],
            ['agreementCheckbox', 'boolean']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array(
            'email' => '',
            'password' => '',
            'agreementCheckbox'=>''
        );
    }

    public function checkCustomerInMagento()
    {
        $endPoint = MAGENTO_URL.'rest/V1/integration/customer/token';
        $headers = array(
            'Accept: application/json',
            'Content-type: application/json'
        );
        $data = [
          "username"=> $this->email,
          "password"=> $this->password
        ];
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
