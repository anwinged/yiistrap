<?php

/**
 * TbAlert class file.
 *
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Bootstrap alert widget.
 *
 * @see http://twitter.github.com/bootstrap/javascript.html#alerts
 */
class TbAlert extends CWidget
{
    /**
     * Alert configuration.
     *
     * You can set up `visible`, `color`, `htmlOptions` per alert apart from widget-level properties.
     *
     * @var array the alerts configurations (style=>config).
     */
    public $alerts;
    /**
     * @var string|bool the close link text. If this is set false, no close link will be displayed.
     */
    public $closeText = TbHtml::CLOSE_TEXT;
    /**
     * @var bool indicates whether the alert should be an alert block. Defaults to 'true'.
     */
    public $block = true;
    /**
     * @var bool indicates whether alerts should use transitions. Defaults to 'true'.
     */
    public $fade = true;
    /**
     * @var string[] the JavaScript event configuration (name=>handler).
     */
    public $events = [];
    /**
     * @var array the HTML attributes for the alert container.
     */
    public $htmlOptions = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        $this->attachBehavior('TbWidget', new TbWidget());
        $this->copyId();
        if (is_string($this->alerts)) {
            $keys = explode(' ', $this->alerts);
        } else {
            if (!isset($this->alerts)) {
                $keys = [
                    TbHtml::ALERT_COLOR_SUCCESS,
                    TbHtml::ALERT_COLOR_WARNING,
                    TbHtml::ALERT_COLOR_INFO,
                    TbHtml::ALERT_COLOR_DANGER,
                ]; // render all styles by default
            }
        }
        if (isset($keys)) {
            $this->alerts = [];
            foreach ($keys as $key) {
                $this->alerts[$key] = [];
            }
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        /* @var $user CWebUser */
        $user = Yii::app()->getUser();
        if (count($user->getFlashes(false)) == 0) {
            return;
        }
        echo TbHtml::openTag('div', $this->htmlOptions);
        foreach ($this->alerts as $key => $alert) {
            if (isset($alert['visible']) && !$alert['visible']) {
                continue;
            }

            if ($user->hasFlash($key)) {
                $htmlOptions = TbArray::popValue('htmlOptions', $alert, []);
                TbArray::defaultValue('closeText', $this->closeText, $htmlOptions);
                TbArray::defaultValue('block', $this->block, $htmlOptions);
                TbArray::defaultValue('fade', $this->fade, $htmlOptions);
                $color = TbArray::getValue('color', $alert, $key);
                echo TbHtml::alert($color, $user->getFlash($key), $htmlOptions);
            }
        }
        echo '</div>';
        $this->registerEvents("#{$this->htmlOptions['id']} > .alert", $this->events);
    }
}
