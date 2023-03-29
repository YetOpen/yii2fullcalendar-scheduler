<?php

/**
 * This class is used to embed FullCalendar Scheduler JQuery Plugin to my Yii2 Projects
 * @copyright Frenzel GmbH - www.frenzel.net
 * @link http://www.frenzel.net
 * @author Philipp Frenzel <philipp@frenzel.net>
 *
 */

namespace yii2fullcalendarscheduler;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\base\Widget as elWidget;
use yii\helpers\ArrayHelper;

class yii2fullcalendarscheduler extends elWidget
{

    /**
     * @var array options the HTML attributes (name-value pairs) for the field container tag.
     * The values will be HTML-encoded using [[Html::encode()]].
     * If a value is null, the corresponding attribute will not be rendered.
     */
    public $options = [
        'class' => 'fullcalendar',
        'theme' => true,
    ];

    /**
     * @var array clientOptions the HTML attributes for the widget container tag.
     */
    public $clientOptions = [
        'weekends' => true,
        'default' => 'month',
        'editable' => false,
        'initialView' => 'timeGridWeek',
    ];

    /**
     * Holds an array of Event Objects
     * @var array events of yii2fullcalendarscheduler\models\Event
     * @todo add the event class and write docs
     **/
    public $events = [];

    /**
     * Define the look n feel for the calendar header, known placeholders are left, center, right
     * @var array header format
     */
    public $headerToolbar = [
        'center' => 'title',
        'left' => 'prev,next today',
        'right' => 'month,agendaWeek'
    ];

    /**
     * Will hold an url to json formatted events!
     * @var url to json service
     */
    public $ajaxEvents = NULL;

    /**
     * wheather the events will be "sticky" on pagination or not
     * @var boolean
     */
    public $stickyEvents = true;

    /**
     * tell the calendar, if you like to render google calendar events within the view
     * @var boolean
     */
    public $googleCalendar = false;

    /**
     * the text that will be displayed on changing the pages
     * @var string
     */
    public $loading = 'Loading ...';

    /**
     * internal marker for the name of the plugin
     * @var string
     */
    private $_pluginName = 'fullCalendar';

    /**
     * The javascript function to us as en eventRender callback
     * @var string the javascript code that implements the eventRender function
     */
    public $eventRender = "";

    /**
     * The javascript function to us as en eventAfterRender callback
     * @var string the javascript code that implements the eventAfterRender function
     */
    public $eventAfterRender = "";

    /**
     * A js callback that triggered when the user clicks an day.
     * @var string the javascript code that implements the dayClick function
     */
    public $dayClick = "";


    /**
     * A js callback will be useful for monitoring when selections are made and cleared
     * @var string the javascript code that implements the dayClick function
     */
    public $select = "";

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        //checks for the element id
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        //checks for the class
        if (!isset($this->options['class'])) {
            $this->options['class'] = 'fullcalendar';
        }
        ArrayHelper::setValue($this->clientOptions, "locale", ArrayHelper::getValue($this->clientOptions, "locale", substr(Yii::$app->language, 0, 2)));
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->options['data-plugin-name'] = $this->_pluginName;

        if (!isset($this->options['class'])) {
            $this->options['class'] = 'fullcalendar';
        }

        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::endTag('div') . "\n";
        $this->registerPlugin();
    }

    /**
     * Registers the FullCalendar javascript assets and builds the requiered js  for the widget and the related events
     */
    protected function registerPlugin()
    {
        $id = $this->options['id'];
        $view = $this->getView();

        /** @var \yii\web\AssetBundle $assetClass */
        $assets = CoreAsset::register($view);

        if (isset($this->options['lang'])) {
            $assets->language = $this->options['lang'];
        }

        if ($this->googleCalendar) {
            $assets->googleCalendar = $this->googleCalendar;
        }

        $js = array();

        if ($this->ajaxEvents != NULL) {
            $this->clientOptions['events'] = $this->ajaxEvents;
        }

        if (is_array($this->headerToolbar) && isset($this->clientOptions['headerToolbar'])) {
            $this->clientOptions['headerToolbar'] = array_merge($this->headerToolbar, $this->clientOptions['headerToolbar']);
        } else {
            $this->clientOptions['headerToolbar'] = $this->headerToolbar;
        }

        $cleanOptions = $this->getClientOptions();
        $js[] = <<<EOCALENDAR
var calendarEl = document.getElementById('$id');
var calendar = new FullCalendar.Calendar(calendarEl, $cleanOptions);
calendar.render();
EOCALENDAR;

        //lets check if we have events to load for the calendar
        if (count($this->events) > 0) {
            foreach ($this->events as $event) {
                $jsonEvent = Json::encode($event);
                $isSticky = $this->stickyEvents;
                $js[] = "jQuery('#$id').fullCalendar('renderEvent',$jsonEvent,$isSticky);";
            }
        }

        $view->registerJs(implode("\n", $js), View::POS_READY);
    }

    /**
     * @return array the options for the text field
     */
    protected function getClientOptions()
    {
        $id = $this->options['id'];
        $options['loading'] = new JsExpression("function(isLoading, view ) {
                jQuery('#{$id}').find('.fc-loading').toggle(isLoading);
        }");
        if ($this->eventRender) {
            $options['eventRender'] = new JsExpression($this->eventRender);
        }
        if ($this->eventAfterRender) {
            $options['eventAfterRender'] = new JsExpression($this->eventAfterRender);
        }
        if ($this->dayClick) {
            $options['dayClick'] = new JsExpression($this->dayClick);
        }
        if ($this->select) {
            $options['select'] = new JsExpression($this->select);
        }
        $options = array_merge($options, $this->clientOptions);
        return Json::encode($options);
    }
}
