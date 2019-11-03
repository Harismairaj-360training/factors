<?php
namespace humhub\modules\api\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\user\models\User;
use humhub\modules\mail\models\forms\CreateMessage;
use humhub\modules\mail\permissions\SendMail;

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

class MessageController extends BaseController
{
    public $modelClass = 'humhub\modules\mail\models\CreateMessage';


    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['update'], $actions['create']);
        return $actions;
    }

    /**
     * Creates a new Message
     */
    public function actionNew()
    {
        prepareRequest();
        $postData = Yii::$app->request->post();

        // Preselect user if userGuid is given
        if (isset($postData['to']) && isset($postData['from']))
        {
            $model = new CreateMessage();
            $to = User::findOne(['username' => $postData['to']]);
            if (isset($to))
            {
                $model->recipient = $to->guid;
                $model->message = $postData["message"];
                $model->title = $postData["title"];
            }

            $from = User::findOne(['username' => $postData['from']]);
            if (isset($from) && $model->validate())
            {
                // Create new Message
                $message = new Message();
                $message->title = $model->title;
                $message->created_by = $from->id;
                $message->save();

                // Attach Message Entry
                $messageEntry = new MessageEntry();
                $messageEntry->message_id = $message->id;
                $messageEntry->user_id = $from->id;
                $messageEntry->created_by = $from->id;
                $messageEntry->content = $model->message;
                $messageEntry->save();

                // Attach also Recipients
                foreach ($model->getRecipients() as $recipient)
                {
                    $userMessage = new UserMessage();
                    $userMessage->message_id = $message->id;
                    $userMessage->user_id = $recipient->id;
                    $userMessage->created_by = $from->id;
                    $userMessage->save();
                }

                // Inform recipients (We need to add all before)
                foreach ($model->getRecipients() as $recipient)
                {
                    try {
                        $message->notify($recipient);
                    } catch(\Exception $e) {
                        return array('result' => 'Could not send notification e-mail to: '. $recipient->username.". Error:". $e->getMessage());
                    }
                }

                // Attach User Message
                $userMessage = new UserMessage();
                $userMessage->message_id = $message->id;
                $userMessage->user_id = $from->id;
                $userMessage->created_by = $from->id;
                $userMessage->is_originator = 1;
                $userMessage->last_viewed = new \yii\db\Expression('NOW()');
                if($userMessage->save())
                {
                  return array('result' => true);
                }
                else
                {
                    return array('result' => 'Something wrong with UserMessage object');
                }
            }
        }

        return array('result' => 'invalid');
    }

}
