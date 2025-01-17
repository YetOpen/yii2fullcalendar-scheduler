yii2fullcalendarscheduler
================

**This project is deprecated, use [yii2fullcalendar](https://github.com/YetOpen/yii2fullcalendar/) which implements Fullcalendar v6.**

JQuery Fullcalendar Scheduler Yii2 Extension
JQuery from: http://arshaw.com/fullcalendar/
Version 2.1.1
License pls. check http://fullcalendar.io/scheduler/download/

JQuery Documentation:
http://arshaw.com/fullcalendar/docs/
Yii2 Extension by <philipp@frenzel.net>

A tiny sample can be found here:
http://yii2fullcalendar.beeye.org

[![Latest Stable Version](https://poser.pugx.org/philippfrenzel/yii2fullcalendar-scheduler/v/stable.svg)](https://packagist.org/packages/philippfrenzel/yii2fullcalendarscheduler)
[![Build Status](https://travis-ci.org/philippfrenzel/yii2fullcalendar-scheduler.svg?branch=master)](https://travis-ci.org/philippfrenzel/yii2fullcalendar-scheduler)
[![Code Climate](https://codeclimate.com/github/philippfrenzel/yii2fullcalendar-scheduler.png)](https://codeclimate.com/github/philippfrenzel/yii2fullcalendar-scheduler)
[![Version Eye](https://www.versioneye.com/php/philippfrenzel:yii2fullcalendarscheduler/badge.svg)](https://www.versioneye.com/php/philippfrenzel:yii2fullcalendarscheduler)
[![License](https://poser.pugx.org/philippfrenzel/yii2fullcalendarscheduler/license.svg)](https://packagist.org/packages/philippfrenzel/yii2fullcalendar-scheduler)

Installation
============
Package is although registered at packagist.org - so you can just add one line of code, to let it run!

add the following line to your composer.json require section:
```json
  "philippfrenzel/yii2fullcalendarscheduler":"*",
```

And ensure, that you have the follwing plugin installed global:

> php composer.phar global require "fxp/composer-asset-plugin:~1.1"

Changelog
---------

29-11-2014 Updated to latest 2.2.3 Version of the library

Usage
=====

Quickstart Looks like this:

```php

  $events = array();
  //Testing
  $Event = new \yii2fullcalendarscheduler\models\Event();
  $Event->id = 1;
  $Event->title = 'Testing';
  $Event->start = date('Y-m-d\TH:m:s\Z');
  $events[] = $Event;

  $Event = new \yii2fullcalendarscheduler\models\Event();
  $Event->id = 2;
  $Event->title = 'Testing';
  $Event->start = date('Y-m-d\TH:m:s\Z',strtotime('tomorrow 6am'));
  $events[] = $Event;

  ?>
  
  <?= \yii2fullcalendarscheduler\yii2fullcalendarscheduler::widget(array(
      'events'=> $events,
  ));
```

Note, that this will only view the events without any detailed view or option to add a new event.. etc.

AJAX Usage
==========
If you wanna use ajax loader, this could look like this:

```php
<?= yii2fullcalendarscheduler\yii2fullcalendarscheduler::widget([
      'options' => [
        'language' => 'de',
        //... more options to be defined here!
      ],
      'ajaxEvents' => Url::to(['/timetrack/default/jsoncalendar'])
    ]);
?>
```

and inside your referenced controller, the action should look like this:

```php
public function actionJsoncalendar($start=NULL,$end=NULL,$_=NULL){

    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $times = \app\modules\timetrack\models\Timetable::find()->where(array('category'=>\app\modules\timetrack\models\Timetable::CAT_TIMETRACK))->all();

    $events = array();

    foreach ($times AS $time){
      //Testing
      $Event = new \yii2fullcalendarscheduler\models\Event();
      $Event->id = $time->id;
      $Event->title = $time->categoryAsString;
      $Event->start = date('Y-m-d\TH:i:s\Z',strtotime($time->date_start.' '.$time->time_start));
      $Event->end = date('Y-m-d\TH:i:s\Z',strtotime($time->date_end.' '.$time->time_end));
      $events[] = $Event;
    }

    return $events;
  }
```
