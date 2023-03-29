<?php

namespace yii2fullcalendarscheduler;

use Yii;
use yii\web\AssetBundle;

/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net>
 */

class CoreAsset extends AssetBundle
{
    /**
     * [$sourcePath description]
     * @var string
     */
    public $sourcePath = '@bower/fullcalendar-scheduler';

    /**
     * the language the calender will be displayed in
     * @var string ISO2 code for the wished display language
     */
    public $language = NULL;

    /**
     * [$autoGenerate description]
     * @var boolean
     */
    public $autoGenerate = true;

    /**
     * tell the calendar, if you like to render google calendar events within the view
     * @todo To be implemented in v6
     * @var boolean
     */
    public $googleCalendar = false;

    /**
     * @var array
     */
    public $js = [
        // Temporary loading with CDN as bower+composer is driving me MAD!
        'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js',
    ];

    public function init()
    {
        // Serve unminified files when YII_DEBUG
        if (YII_DEBUG) {
            foreach ($this->js as $jsk => $jsfile) {
                $this->js[$jsk] = str_replace(".min", "", $jsfile);
            }
        }
    }
}
