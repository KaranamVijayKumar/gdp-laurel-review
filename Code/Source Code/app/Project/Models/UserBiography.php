<?php
/**
 * File: UserBiography.php
 * Created: 18-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Html2Text\Html2Text;
use Story\Auth;
use Story\Error;
use Story\ORM;
use Story\Validator;

class UserBiography extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'user' => '\Project\Models\User',
    );

    /**
     * @var string
     */
    protected static $table = 'user_biography';


    /**
     * Validates and Updates the biography
     *
     * @param array $input
     *
     *
     * @param User  $user
     *
     * @return bool|string
     */
    public function updateBiography(array $input, User $user = null)
    {

        $input = array_map('trim', $input);

        $validator = new Validator($input);

        $validator->rule('required', 'content')
            ->message(_('Short biography is required.'));
        $validator->rule('lengthMax', 'content', 65535)
            ->message('Short biography cannot be more than 65535 characters.');


        // if validation passes, we save and redirect
        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();


                $this->content = $input['content'];

                $text = new Html2Text($input['content']);
                $this->content_text = trim($text->getText());

                $this->save();

                event('account.updated', array($user ? $user : Auth::user(), _('Biography')));

                // Commit Transaction
                static::$db->pdo->commit();

                return true;

            } catch (\Exception $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);

                return false;
            }
        }

        return $validator->errorsToNotification();
    }
}
