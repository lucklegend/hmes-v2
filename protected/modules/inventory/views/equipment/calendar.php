<h1>Schedule</h1>

<?php $this->widget('ext.fullcalendar.EFullCalendarHeart', array(
    'themeCssFile'=>'cupertino/jquery-ui.min.css',
    'options'=>array(
        'id'=>"mycal",
        'header'=>array(
            'left'=>'prev,next,today',
            'center'=>'title',
            'right'=>'month,agendaWeek,agendaDay',
            
        ),
        //'mintime'=> "24:00:00",
        
        'events'=>$this->createUrl('/inventory/equipment/calendarView'), // URL to get event
    )));
?>
