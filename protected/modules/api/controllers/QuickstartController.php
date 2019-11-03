<?php
namespace humhub\modules\api\controllers;

use humhub\modules\user\models\forms\AccountLogin;
use humhub\modules\user\models\forms\Registration;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Profile;
use humhub\modules\api\models\Group;
use Yii;

function object_to_array($object)
{
    if($object == null)
    {
        return null;
    } else if (is_object($object)) {
        return array_map(__FUNCTION__, get_object_vars($object));
    } else if (is_array($object)) {
        return array_map(__FUNCTION__, $object);
    } else {
        return $object;
    }
}

function prepareRequest()
{
    $request = Yii::$app->request;
    if ($request->isPost && empty($request->getBodyParams())) {
        $request->setBodyParams(object_to_array(json_decode($request->getRawBody())));
    }
}

class QuickstartController extends BaseController
{
    public $modelClass = 'humhub\modules\api\models\User';

    const USERGROUP_ADMIN = 'admin';
    const USERGROUP_MODERATOR = 'moderator';
    const USERGROUP_MEMBER = 'member';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['delete'], $actions['update'], $actions['create']);
        return $actions;
    }

    /**
     * To Register a user.
     * @return mixed
     */
    public function actionExperts()
    {
        prepareRequest();
        $adminUser = $this->fetchAdmin();
        $request = Yii::$app->request;
        $result = array();
        $result['csrfToken'] = $request->csrfToken;

        //  Existing User
        $loggedInUser = $this->fetchLoggedInUser();
        if($loggedInUser)
        {
            //  Update User Profile
            $userModel = new User();
            $userModel->scenario = 'editProfile';
            $userModel->id = $loggedInUser->id;
            $profileModel = $userModel->profile;
            $profileModel->firstname = $request->post()["Profile"]["firstname"];
            $profileModel->lastname = $request->post()["Profile"]["lastname"];
            if($profileModel->save())
            {
              $result['User'] = $loggedInUser;

              //  Check User Group
              if(!empty($request->post()["UserGroup"]))
              {
                $canSetGroup = Group::findGroup($result['User']['id'],$request->post()["UserGroup"]);
                if($canSetGroup)
                {
                  //  Set User Group
                  $this->setGroup($request,$result['User']['id']);
                }
              }

              //  Create New Space(s) if Not Already Exists
              $this->createOrUpdateSpaces($loggedInUser->id);

              //  Download image from provide url and Download into humhub server & change file name
              if(!empty($request->post()["Profile"]["image"]))
              {
                $directoryPath = realpath(__DIR__ . '/../../../..').'/uploads/profile_image/';
                $downloadedImage = file_get_contents($request->post()["Profile"]["image"]);
                $imageFile = $directoryPath.$loggedInUser->guid.".jpg";
                $result['dpPath'] = $imageFile;
                $fp = fopen($imageFile, "w");
                fwrite($fp, $downloadedImage);
                fclose($fp);
              }
              
              return $result;
            }
        }

        //  Create New User
        $registration = new Registration();
        $registration->enableEmailField = true;
        $registration->enableUserApproval = false;
        if($registration->submitted() && $registration->validate())
        {
            $registration->getUser()->created_by = $adminUser->id;
            $registration->getUser()->updated_by = $adminUser->id;
            if($registration->register())
            {
                $result = array();
                $result['User'] = $registration->getUser();

                //  Set User Group
                $this->setGroup($request,$result['User']['id']);

                //  Create New Space if Not Already Exists
                $this->createOrUpdateSpaces($registration->getUser()->id);
                return $result;
            }
        }

        //  Enrollment failed
        if(!empty($registration->getUser()->getErrors())) {
            return $registration->getUser();
        } else if(!empty($registration->getPassword()->getErrors())) {
            return $registration->getPassword();
        } else if(!empty($registration->getProfile()->getErrors())) {
            return $registration->getProfile();
        }
    }

    private function createOrUpdateSpaces($userId)
    {
        $request = Yii::$app->request;
        try {

          //  Additions
          if(!empty($request->post()["Spaces"]["addIn"]))
          {
            foreach($request->post()["Spaces"]["addIn"] as $addIn)
            {
              if(!empty($addIn['guid']))
              {
                $cSpace = $this->fetchSpace($addIn['guid']);
                if($cSpace)
                {
                    $cSpace->addMember($userId);

                    $membership = Membership::findOne(['space_id' => $cSpace->id, 'user_id' => $userId]);
                    $membership['group_id'] = self::USERGROUP_MODERATOR;
                    $membership->save();
                }
                else
                {
                    $adminUser = $this->fetchAdmin();
                    Yii::$app->user->permissionManager->subject = $adminUser;
                    $space = new Space();
                    $space->guid = $addIn['guid'];
                    $space->name = $addIn['name'];
                    $space->description = $addIn['description'];
                    $space->join_policy = 2;
                    $space->visibility = 2;
                    $space->created_by = $adminUser->id;
                    $space->updated_by = $adminUser->id;
                    $space->auto_add_new_members = 1;
                    $space->color = '#333';
                    if($space->save())
                    {
                        $space->addMember($userId);

                        $membership = Membership::findOne(['space_id' => $space->id, 'user_id' => $userId]);
                        $membership['group_id'] = self::USERGROUP_MODERATOR;
                        $membership->save();
                    }
                }
              }
            }
          }

          //  Removing
          if(!empty($request->post()["Spaces"]["removeFrom"]))
          {
            foreach($request->post()["Spaces"]["removeFrom"] as $removeFrom)
            {
              if(!empty($removeFrom['guid']))
              {
                $cSpace = $this->fetchSpace($removeFrom['guid']);
                if($cSpace)
                {
                    $cSpace->addMember($userId);

                    $membership = Membership::findOne(['space_id' => $cSpace->id, 'user_id' => $userId]);
                    $membership['group_id'] = self::USERGROUP_MEMBER;
                    $membership->save();
                }
              }
            }
          }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * To Register a user.
     * @return mixed
     */
    public function actionEnrolUser()
    {
        prepareRequest();
        $adminUser = $this->fetchAdmin();
        $request = Yii::$app->request;
        $result = array();
        $result['csrfToken'] = $request->csrfToken;

        //  Existing User
        $loggedInUser = $this->fetchLoggedInUser();
        if($loggedInUser)
        {
            $result['User'] = $loggedInUser;

            //  Check User Group
            if(!empty($request->post()["UserGroup"]))
            {
              $canSetGroup = Group::findGroup($result['User']['id'],$request->post()["UserGroup"]);
              if($canSetGroup)
              {
                //  Set User Group
                $this->setGroup($request,$result['User']['id']);
              }
            }

            //  Create New Space if Not Already Exists
            $currentSpace = $this->fetchCurrentSpace();
            if($currentSpace)
            {
                //  Add Member in the Space
                $result['Space'] = $currentSpace;
                $currentSpace->addMember($loggedInUser->id);
            }
            return $result;
        }

        //  Create New User
        $registration = new Registration();
        $registration->enableEmailField = true;
        $registration->enableUserApproval = false;
        if($registration->submitted() && $registration->validate())
        {
            $registration->getUser()->created_by = $adminUser->id;
            $registration->getUser()->updated_by = $adminUser->id;
            if($registration->register())
            {
                $result = array();
                $result['User'] = $registration->getUser();

                //  Set User Group
                $this->setGroup($request,$result['User']['id']);

                //  Create New Space if Not Already Exists
                $currentSpace = $this->fetchCurrentSpace();
                if($currentSpace)
                {
                    //  Add Member in the Space
                    $result['Space'] = $currentSpace;
                    $currentSpace->addMember($registration->getUser()->id);
                }
                return $result;
            }
        }

        //  Enrollment failed
        if(!empty($registration->getUser()->getErrors())) {
            return $registration->getUser();
        } else if(!empty($registration->getPassword()->getErrors())) {
            return $registration->getPassword();
        } else if(!empty($registration->getProfile()->getErrors())) {
            return $registration->getProfile();
        }
    }

    /**
     * Echo input.
     * @return mixed
     */
    public function actionEcho()
    {
        prepareRequest();
        $request = Yii::$app->request;
        return $request->post();
    }

    private function setGroup($request,$userId)
    {
      //  Get Group ID
      if(!empty($request->post()["UserGroup"]))
      {
        $userGroup = $request->post()["UserGroup"];
        $userGroup = Group::getAllGroups($userGroup);
        if(!empty($userGroup))
        {
          if(!empty($userGroup[0]->id) && !empty($userId))
          {
            $GroupUpdated = Group::setUserGroup($userId,$userGroup[0]->id);
            return $GroupUpdated;
          }
        }
      }
    }

    private function fetchAdmin()
    {
        return $this->fetchUser('admin');
    }

    private function fetchLoggedInUser()
    {
        $request = Yii::$app->request;
        if(array_key_exists("User", $request->post()) && array_key_exists("username", $request->post()["User"]))
        {
            return $this->fetchUser($request->post()["User"]["username"]);
        }
    }

    private function fetchCurrentSpace()
    {
        $request = Yii::$app->request;
        if(array_key_exists("Space", $request->post()) && array_key_exists("guid", $request->post()["Space"])) {
            $currentSpace = $this->fetchSpace($request->post()["Space"]["guid"]);
            if($currentSpace) {
               return $currentSpace;
            } else if (array_key_exists("name", $request->post()["Space"])) {
                $adminUser = $this->fetchAdmin();
                Yii::$app->user->permissionManager->subject = $adminUser;
                $space = new Space();
                $space->visibility = 2;
                $space->join_policy = 2;
                if($space->load($request->post())) {
                    $space->created_by = $adminUser->id;
                    $space->updated_by = $adminUser->id;
                    $space->color = "#333";
                    if($space->save()) {
                        return $space;
                    }
                }
            }
        }
    }

    private function fetchSpace($guid)
    {
        return Space::find()->where(['guid' => $guid])->one();
    }

    private function fetchUser($userName)
    {
        return User::find()->where(['username' => $userName])->one();
    }

}
