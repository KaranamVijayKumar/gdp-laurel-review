<?php
/**
 * File: Biography.php
 * Created: 18-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Account;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\UserBiography;
use Story\Auth;
use Story\Dispatch;

class Biography extends AdminBaseController
{

    /**
     * @var UserBiography
     */
    public $biography;

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/account/biography';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @var \Project\Models\User
     */
    public $user;

    /**
     * Constructor
     *
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        $this->user = Auth::user();

        return parent::__construct($route, $dispatch);
    }

    /**
     * View the bio page
     */
    public function get()
    {

        $this->title = _('Account');

        $this->biography = UserBiography::one(array('user_id' => $this->user->id));

        if (!$this->biography) {
            $this->biography = new UserBiography;
            $this->biography->set(array('content' => '', 'content_text' => ''));
        }

    }

    /**
     * Processes the user bio
     */
    public function post()
    {

        try {

            $this->biography = UserBiography::one(array('user_id' => $this->user->id));

            if (!$this->biography) {
                $this->biography = new UserBiography;
                $this->biography->set(array('content' => '', 'content_text' => '', 'user_id' => $this->user->id));
            }

            // Update the bio
            if (($result = $this->biography->updateBiography($_POST)) === true) {

                redirect(
                    action('\Project\Controllers\Admin\Account\Biography'),
                    array('notice' => _('Biography updated.'))
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Admin\Account\Biography'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            // redirect back to the user dashboard
            redirect(action('\Project\Controllers\Admin\Account\Dashboard'), array('error' => $e->getMessage()));
        }
    }
}
