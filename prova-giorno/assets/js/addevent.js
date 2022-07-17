 
 var addEvent = function addEvent(title, priority) {
            title = title.length === 0 ? "Untitled Event" : title;

            priority = priority.length === 0 ? "label label-default" : priority;

            var html = $('<li class="external-event"><span class="' + priority + '">' + title + '</span></li>');

            jQuery('#external-events').append(html);
            initDrag(html);
        };

        /* initialize the external events
         -----------------------------------------------------------------*/

        $('#external-events li.external-event').each(function () {
            initDrag($(this));
        });

        $('#add-event').click(function () {
            var title = $('#title').val();
            var priority = $('input:radio[name=priority]:checked').val();
            addEvent(title, priority);
        });
		
		 var initDrag = function initDrag(e) {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end


            var eventObject = {
                title: $.trim(e.text()), // use the element's text as the event title

                className: $.trim(e.children('span').attr('class')) // use the element's children as the event class
            };
            // store the Event Object in the DOM element so we can get to it later
            e.data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            e.draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0 //  original position after the drag
            });
        };

       