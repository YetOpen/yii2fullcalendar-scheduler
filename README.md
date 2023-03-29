# yii2fullcalendarscheduler

Embed [fullcalendar](https://fullcalendar.io) as Yii2 widget
Version 6.0.0-ALPHA
Check usage license at [fullcalendar.io](https://fullcalendar.io/license).

Yii2 Extension by <philipp@frenzel.net>, updated for v6 by YetOpen.

## Installation

Package is although registered at packagist.org - so you can just add one line of code, to let it run!

add the following line to your `composer.json` require section:
```json
  "yetopen/yii2fullcalendarscheduler":"*",
```

And ensure, that you have the follwing plugin installed global:

> php composer.phar global require "fxp/composer-asset-plugin:~1.1"

## Changelog

2023.03.29 Forked for v6.1.5

## Usage

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

  echo \yii2fullcalendarscheduler\yii2fullcalendarscheduler::widget(array(
      'events'=> $events,
  ));
```

Note, that this will only view the events without any detailed view or option to add a new event.. etc.

## AJAX Usage
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
