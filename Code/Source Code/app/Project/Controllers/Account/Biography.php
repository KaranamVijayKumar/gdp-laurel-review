<?php
/**
 * File: Biography.php
 * Created: 18-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Account;

use Project\Controllers\AbstractPage;
use Project\Models\UserBiography;
use Story\Auth;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Biography
 *
 * @package Project\Controllers\Account
 */
class Biography extends AbstractPage
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
    public $template = 'account/biography';

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

        try {
            $this->biography = UserBiography::one(array('user_id' => $this->user->id));

            if (!$this->biography) {
                $this->biography = new UserBiography;
                $this->biography->set(array('content' => '', 'content_text' => ''));
            }

            $this->buildPage(null, $this->template);

        } catch (NotFoundException $e) {
            $this->title = _('Biography');

        } catch (\Exception $e) {
            Error::exception($e);
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

            // Update the email and name
            if (($result = $this->biography->updateBiography($_POST)) === true) {

                redirect(
                    action('\Project\Controllers\Account\Biography'),
                    array('notice' => _('Biography updated.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Account\Biography'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            // redirect back to the user dashboard
            redirect(action('\Project\Controllers\Account\Dashboard'), array('error' => $e->getMessage()));
        }
    }
}
