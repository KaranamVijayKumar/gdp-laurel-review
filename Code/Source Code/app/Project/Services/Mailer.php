<?php
/**
 * File: Mailer.php
 * Created: 24-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services;

use Story\Cipher;

/**
 * Class Mailer
 *
 * @package Project\Services
 */
class Mailer extends \PHPMailer
{
    /**
     * Constructor
     *
     * @param bool $exceptions Should we throw external exceptions?
     */
    public function __construct($exceptions = false)
    {

        require_once SP . 'Project/Support/Vendor/PHPMailer/PHPMailerAutoload.php';

        parent::__construct($exceptions);

        $this->CharSet = 'UTF-8';

        // set the smtp if needed
        if (config('smtp')) {
            $this->isSMTP();
            $this->Host = config('smtp_host');
            if (config('smtp_auth')) {

                $this->SMTPAuth = true;
                $this->Username = config('smtp_username', '');
                $this->Password = Cipher::decrypt(base64_decode(config('smtp_password')));

            }

            $this->SMTPSecure = config('smtp_secure', '');
            $this->Port = config('smtp_port', 25);

        }

        // set the from
        $this->setFrom(config('mail_from'), config('mail_from_name'));
    }

    /**
     * Sends a mail instantly
     *
     * @param callable|\Closure $function
     * @param bool $exceptions
     * @return mixed
     * @throws \Exception
     * @throws \phpmailerException
     */
    public static function sendMail(\Closure $function, $exceptions = false)
    {

        $mail = new static ($exceptions);
        call_user_func($function, $mail);
        return $mail->send();
    }
}
