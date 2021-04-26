/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_EventManager
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
define(
    [
        'jquery',
        'Webkul_EventManager/js/jquery.calendario',
        'Webkul_EventManager/js/modernizr.custom.63321' 
     ], function ($) {
        var globalThis;
        $.widget(
            'Wekul.EventManager', 
            {
                _create: function (calendario,modernizr) {
                    var self = this;
                    globalThis = this;
                    var data = this.options.data;
                    var monthName = this.options.data2.monthName;
                    this.options.transEndEventNames[Modernizr.prefixed('transition')],
                    $wrapper = $('#custom-inner'),
                    $calendar = $('#calendar'),
                    cal = $calendar.calendario(
                        {
                            onDayClick : function ( $el, $contentEl, dateProperties ) {
                                if($contentEl.length > 0 ) {
                                    self.showEvents($contentEl, dateProperties);
                                }
                            },
                            caldata : data, // here put data to show on calendar
                            displayWeekAbbr : true
                        } 
                    ),
                    $month = $('#custom-month').html(cal.getMonthName()),
                    $year = $('#custom-year').html(cal.getYear());
              
                    $('#custom-next').on(
                        'click', function () {
                            cal.gotoNextMonth(self.updateMonthYear);
                        } 
                    );
                    $('#custom-prev').on(
                        'click', function () {
                            cal.gotoPreviousMonth(self.updateMonthYear);
                        } 
                    );
                    self.getSelectedGroupId();
                    self.getSelectedEventId();
                    self.gotoCurrentDate();
                },
                updateMonthYear: function () {                
                        $month.html(cal.getMonthName());
                        $year.html(cal.getYear());
                },
                showEvents: function ( $contentEl, dateProperties ) {
                        globalThis.hideEvents();
                        var $events = $('<div id="custom-content-reveal" class="custom-content-reveal"><h4>Events for ' + dateProperties.monthname + ' ' + dateProperties.day + ', ' + dateProperties.year + '</h4></div>'),
                        $close = $('<span class="custom-content-close"></span>').on('click', globalThis.hideEvents);
                        $events.append($contentEl.html() , $close).insertAfter($('#custom-inner'));
                        setTimeout(
                            function () {
                                    $events.css('top', '0%');
                            }, 25 
                        );
                },
                hideEvents: function () {
                        var transEndEventName = globalThis.options.transEndEventNames;
                        var $events = $('#custom-content-reveal');
                    if($events.length > 0 ) {    
                        $events.css('top', '100%');
                        Modernizr.csstransitions ? $events.on(
                            transEndEventName, function () {
                                $(globalThis).remove(); }
                        ) : $events.remove();
                    }
                },
                /**
                 * This function reload current page with pass data set in url that is use to load data according to id
                 */
                getSelectedGroupId: function () {
                    $("body").on(
                        'change',".wk_select",function () {
                            var str = $('#wk-event-group-select').children(":selected").attr("value");
                            window.location.href=str;
                        }
                    );
                },
                getSelectedEventId: function () {
                    $("body").on(
                        'change',".wk_select2",function () {
                            var str = $('#wk-event-redirect').children(":selected").attr("value");
                            window.location.href=str;
                        }
                    );
                },
                gotoCurrentDate: function () {
                    $(".wk_today").click(
                        function () {
                            var str = $("#wk-event-group-select").children()[0].value;
                            window.location.href=str;
                        }
                    );
                }
            }
        );
        return $.Wekul.EventManager;
     }
);