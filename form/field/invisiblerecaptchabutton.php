<?php

namespace BestProject\Wordpress\Form\Field;

defined('ABSPATH') or die;

use BestProject\Wordpress\Form\Field,
	BestProject\Wordpress\Language;

/**
 * InvisibleRecaptchaButton field type.
 */
class InvisibleRecaptchaButton extends Field
{

    protected $hide_label = true;

    protected $site_key = '';
    protected $secret_key = '';

	/**
	 * Get this fields input
	 */
	public function getInput()
	{
        $this->id = $this->name;
        $id       = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
        $class    = (!empty($this->class) ? ' class="g-recaptcha '.$this->class.'"'
                : 'class="g-recaptcha"');
        ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
            function submit<?php echo $this->id ?>(){
                var $el = document.getElementById('<?php echo $this->id ?>');
                console.log($el.form);
                $el.form.dispatchEvent(new Event('submit'));
            }
        </script>
        <button
            data-sitekey="<?php echo $this->site_key ?>"
            data-callback="submit<?php echo $this->id ?>"
            data-badge="bottomleft"
            name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $class ?>><?php
            echo $this->value
        ?></button><?php
	}

    /**
     * Validate captcha.
     */
    public function validate() {

        $parent_verification = parent::validate();

        if( $parent_verification!==true ) {
            return $parent_verification;
        }

        $captcha_verification = $this->getVerification();

        if( !$captcha_verification ) {
            return Language::_('FORM_FIELD_INVISIBLE_RECAPTCHA_INVALID');
        }

        return true;
    }

    /**
     * Check provided captcha input.
     * 
     * @return  Boolean
     */
    public function getVerification(){

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }

        $query = [
            'secret'=>$this->secret_key,
            'response'=>filter_input(INPUT_POST, 'g-recaptcha-response'),
            'remoteip'=>$ipAddress
        ];

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?'.http_build_query($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($data);

        return (bool)$response->success;
    }
}
