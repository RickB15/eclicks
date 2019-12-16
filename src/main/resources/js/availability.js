const ID = function () {
    return '_' + Math.random().toString(36).substr(2, 9);
};

const getWeekDay = function (day) {
    const curr = new Date; // get current date
    const first = curr.getDate() - curr.getDay() + 1; // First day is the day of the month - the day of the week. +1 because monday is first
    const last = first + 6; // last day is the first day + 6

    switch (day) {
        case 'last':
            return last;
    
        default: //first day
            return first;
    }
}

const getLocale = function () {
    switch (locale) {
        case 'dutch':
            return 'nl';
    
        default:
            return 'en';
    }
}

const defaultSettings = function() {
    return {
        themeSystem: 'bootstrap',
        height: 'auto',
        defaultView: 'timeGridWeek',
        minTime: '06:00:00',
        maxTime: '22:00:00',
        firstDay: 1, //monday
        locale: getLocale(),
        timeZone: 'UTC', //TODO change this to timezone in database
        slotEventOverlap: false,
        scrollTime: '07:00:00',
        editable: true,
        droppable: true,
        selectable: true,
        selectMirror: true,
        selectOverlap: false,
        buttonIcons: {
            //fontawesome icons
            prev: 'fa-angle-left',
            next: 'fa-angle-right',
            prevYear: 'fa-angle-double-left',
            nextYear: 'fa-angle-double-right'
        },
        slotLabelFormat: [
            {
                hour: '2-digit',
                minute: '2-digit',
                omitZeroMinute: false,
                hour12: false
            }
        ],
        customButtons: {
            resetCalendar: {
                text: globalLang.reset,
                click: function () {
                    //TODO !! warning message !! Do you wanna clear?
                    let events = calendar.getEvents();
                    let deleteEvents = [];
                    events.forEach(event => {
                        event.remove();
                        deleteEvents.push(event);
                    });
                    if (!isEmpty(deleteEvents)) {
                        updateCalendar('schedular', deleteEvents, 'delete');
                    }
                }
            },
            resetSpecificCalendar: {
                text: globalLang.reset,
                click: function () {
                    //TODO !! warning message !! Do you wanna clear?
                    let events = calendarSpecific.getEvents();
                    let deleteEvents = [];
                    events.forEach(event => {
                        event.remove();
                        deleteEvents.push(event);
                    });
                    if (!isEmpty(deleteEvents)) {
                        updateCalendar('schedular-specific', deleteEvents, 'delete');
                    }
                }
            }
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            omitZeroMinute: false,
            hour12: false
        },
        eventOverlap: false
    }
}

const defaultEvents = function () {
    return {
        select: function (info) {
            const event = this.addEvent({
                id: ID(),
                start: info.start,
                end: info.end,
                allDay: info.allDay
            });
            this.unselect();
            // console.log(event);
            updateCalendar(this.el.id, event, 'set');
        },
        eventDrop: function (eventDropInfo) {
            const thisCalendar = eventDropInfo.event._calendar;
            const groupId = eventDropInfo.event._def.groupId;
            if (!isEmpty(groupId)) {
                const events = thisCalendar.getEvents();
                events.forEach(event => {
                    if (event.groupId === groupId) {
                        updateCalendar(thisCalendar.el.id, event, 'update');
                    }
                });
            } else {
                updateCalendar(thisCalendar.el.id, eventDropInfo.event, 'update');
            }
        },
        eventResize: function (eventResizeInfo) {
            const thisCalendar = eventResizeInfo.event._calendar;
            const groupId = eventResizeInfo.event._def.groupId;
            if (!isEmpty(groupId)) {
                const events = thisCalendar.getEvents();
                events.forEach(event => {
                    if (event.groupId === groupId) {
                        updateCalendar(thisCalendar.el.id, event, 'update');
                    }
                });
            } else {
                updateCalendar(thisCalendar.el.id, eventResizeInfo.event, 'update');
            }
        },
        eventClick: function (info) {
            const thisCalendar = info.event._calendar;
            const event = info.event;
            const maxAmount = getWeekDay('last') - event.start.getDate(); //TODO no max for specific

            makeModal(info.event, maxAmount);
            $('#' + event.id).modal('show');

            $('#delete').click(function () {
                $('#' + event.id).modal('hide');
                event.remove();
                updateCalendar(thisCalendar.el.id, event, 'delete');
                setTimeout(function () {
                    $('#' + event.id).remove();
                }, 300);
            });

            $('#delete-all').click(function () {
                $('#' + event.id).modal('hide');
                let oldEvents = thisCalendar.getEvents();
                let deleteEvents = [];
                for (let index = 0; index < oldEvents.length; index++) {
                    const oldEvent = oldEvents[index];

                    if (oldEvent.groupId === event.groupId) {
                        deleteEvents.push(oldEvent);
                        oldEvent.remove();
                    }
                }
                if (!isEmpty(deleteEvents)) {
                    updateCalendar(thisCalendar.el.id, deleteEvents, 'delete');
                }
                setTimeout(function () {
                    $('#' + event.id).remove();
                }, 300);
            });

            $('#close').click(function () {
                $('#' + event.id).modal('hide');
                setTimeout(function () {
                    $('#' + event.id).remove();
                }, 300);
            });

            $('#cancel').click(function () {
                $('#' + event.id).modal('hide');
                setTimeout(function () {
                    $('#' + event.id).remove();
                }, 300);
            });

            $('#duplicate').click(function () {
                $('#' + event.id).modal('hide');
                const amount = $('#amount').val();
                const groupId = ID();
                const max = getWeekDay('last') - event.start.getDay() - 1; //remove day clicked //TODO no max for specific
                let oldEvents = thisCalendar.getEvents();
                let addEvents = [];
                let exist = false;
                let added = false;
                for (i = 0; i < amount; i++) {
                    //extra check if not > max amount
                    if( i <= max ){
                        let newStart = moment(event.start).clone().add(i + 1, 'days')._d;
                        let newEnd = moment(event.end).clone().add(i + 1, 'days')._d;

                        //check if event start/end time isn't between other events
                        oldEvents.forEach(oldEvent => {
                            if (newStart <= oldEvent.start && newEnd >= oldEvent.end || newStart >= oldEvent.start && newEnd <= oldEvent.end) {
                                exist = true;
                                //TODO error handling event exist already
                            }
                        });

                        if (exist === false) {
                            let newEvent = {
                                id: ID(),
                                groupId: groupId,
                                classNames: 'fc-day-group',
                                start: newStart,
                                end: newEnd,
                                allDay: event.allDay
                            };
                            event._calendar.addEvent({
                                ...newEvent
                            });
                            addEvents.push(newEvent);
                            added = true;
                        }
                    }
                }
                //if another element is added to the calendar, set the clicked date also in the group
                if (added === true) {
                    event.setProp('groupId', groupId);
                    event.setProp('classNames', 'fc-day-group');
                    updateCalendar(thisCalendar.el.id, event, 'update');
                    updateCalendar(thisCalendar.el.id, addEvents, 'set');
                } else {
                    //TODO error handling no group has been made
                }
                thisCalendar.render();
                setTimeout(function () {
                    $('#' + event.id).remove();
                }, 300);
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        renderCalendar();
    }, 300);
});

function renderCalendar() {
    var calendarEl = document.getElementById('schedular');

    let calendarSettings = {
        plugins: ['interaction', 'timeGrid', 'bootstrap', 'list'],
        header: {
            left: '',
            center: '',
            right: 'resetCalendar'
        },
        columnHeaderFormat: {
            weekday: 'short'
        },
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                type: 'GET',
                url: base_url + 'settings/json_get_availability',
                dataType: 'json',
                error: function (xhr, errorType, exception) {
                    if (xhr.status && xhr.status == 400) {
                        alert(xhr.responseText);
                    } else {
                        //TODO better error handling
                        alert(globalLang.something_went_wrong);
                    }
                },
                success: successCallback
            });
        }
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        ...defaultSettings(),
        ...calendarSettings,
        ...defaultEvents()
    });

    //if specific-tab is been clicked make the calendar. function only once executed by checking if class has fc
    $('#specific-tab').click(function () {
        if (!document.getElementById('schedular-specific').classList.contains('fc')) {
            setTimeout(function () {
                renderSpecificCalendar();
            }, 300);
        }
    });

    calendar.render();
}

function renderSpecificCalendar() {
    var calendarElSpecific = document.getElementById('schedular-specific');
    
        let calendarSpecificSettings = {
            plugins: ['interaction', 'timeGrid', 'bootstrap'],
            header: {
                left: 'prev',
                center: 'title',
                right: 'resetSpecificCalendar today next'
            },
            footer: {
                left: 'prev',
                center: '',
                right: 'next'
            },
            titleFormat: {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            },
            columnHeaderFormat: {
                weekday: 'short',
                day: 'numeric'
            },
            validRange: function (nowDate) {
                return {
                    start: nowDate
                };
            },
            events: function (info, successCallback, failureCallback) {
                $.ajax({
                    type: 'GET',
                    url: base_url + 'settings/json_get_specific_availability',
                    dataType: 'json',
                    error: function (xhr, errorType, exception) {
                        if (xhr.status && xhr.status == 400) {
                            alert(xhr.responseText);
                        } else {
                            //TODO better error handling
                            alert(globalLang.something_went_wrong);
                        }
                    },
                    success: successCallback
                });
            }
        }
    
    var calendarSpecific = new FullCalendar.Calendar(calendarElSpecific, {
        ...defaultSettings(),
        ...calendarSpecificSettings,
        ...defaultEvents()
    });

    calendarSpecific.render();
}

function makeModal(info, maxAmount) {
    let modal = document.createElement("DIV");
    let modalDialog = document.createElement("DIV");
    let modalContent = document.createElement("DIV");
    let modalHeader = document.createElement("DIV");
        let modalTitle = document.createElement("H5");
        let buttonClose = document.createElement("BUTTON");
        let closeIcon = document.createElement("SPAN");
    let modalBody = document.createElement("DIV");
        let inputGroup = document.createElement("DIV");
        let amount = document.createElement("INPUT");
        let inputGroupAppend = document.createElement("DIV");
        let buttonDuplicate = document.createElement("BUTTON");
    let modalFooter = document.createElement("DIV");
        let buttonDelete = document.createElement("BUTTON");
        let buttonCancel = document.createElement("BUTTON");

    setAttributes(modal, {
        id: info.id,
        class: 'modal fade',
        tabindex: '-1',
        role: 'dialog',
        aria_labelledby: 'modal',
        aria_hidden: 'true'
    });
    setAttributes(modalDialog, {
        class: 'modal-dialog modal-dialog-centered modal-dialog-scrollable',
        role: 'document'
    });
    setAttributes(modalContent, {
        class: 'modal-content'
    });
    setAttributes(modalHeader, {
        class: 'modal-header'
    });
        setAttributes(modalTitle, {
            id: 'modelLongTitle',
            class: 'modal-title'
        });
        setAttributes(buttonClose, {
            id: 'close',
            type: 'button',
            class: 'close',
            data_dismiss: 'modal',
            aria_label: 'Close'
        });
        setAttributes(closeIcon, {
            aria_hidden: 'true'
        });
    setAttributes(modalBody, {
        class: 'modal-body'
    });
        setAttributes(inputGroup, {
            class: 'input-group'
        });
        setAttributes(amount, {
            id: 'amount',
            class: 'form-control',
            type: 'number',
            name: 'amount',
            min: '1',
            max: maxAmount,
            value: '1'
        });
        setAttributes(inputGroupAppend, {
            class: 'input-group-append'
        });
        setAttributes(buttonDuplicate, {
            id: 'duplicate',
            type: 'button',
            class: 'btn btn-primary'
        });
    setAttributes(modalFooter, {
        id:   'modal-footer',
        class: 'modal-footer'
    });
        setAttributes(buttonDelete, {
            id: 'delete',
            type: 'button',
            class: 'btn btn-danger'
        });
        setAttributes(buttonCancel, {
            id: 'cancel',
            type: 'button',
            class: 'btn btn-secondary'
        });

    modalTitle.innerHTML = toFirstUpperCase(globalLang.edit) + ' ' + globalLang.availability;
    closeIcon.innerHTML = '&times;';
    buttonDuplicate.innerHTML = toFirstUpperCase(globalLang.duplicate);
    buttonDelete.innerHTML = toFirstUpperCase(globalLang.delete);
    buttonCancel.innerHTML = toFirstUpperCase(globalLang.cancel);

    buttonClose.appendChild(closeIcon);

    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(buttonClose);

    inputGroupAppend.appendChild(buttonDuplicate);
    inputGroup.appendChild(amount);
    inputGroup.appendChild(inputGroupAppend);
    modalBody.appendChild(inputGroup);

    modalFooter.appendChild(buttonDelete);
    modalFooter.appendChild(buttonCancel);

    modalContent.appendChild(modalHeader);
    modalContent.appendChild(modalBody);
    modalContent.appendChild(modalFooter);

    modalDialog.appendChild(modalContent);

    modal.appendChild(modalDialog);

    document.body.appendChild(modal);

    if (!isEmpty(info.groupId)) {
        let buttonDeleteAll = document.createElement("BUTTON");
        setAttributes(buttonDeleteAll, {
            id: 'delete-all',
            type: 'button',
            class: 'btn btn-danger'
        });
        buttonDeleteAll.innerHTML = toFirstUpperCase(globalLang.delete) + ' ' + globalLang.all;
        document.getElementById('modal-footer').prepend(buttonDeleteAll);
    }
}

function updateCalendar(calendarId, events, action) {
    let formatEvents = [];
    let specific = false;
    if (calendarId === 'schedular-specific') {
        specific = true;
    }

    if (events.length > 0) {
        for (let index = 0; index < events.length; index++) {
            const event = events[index];
            formatEvents.push({
                id: event.id,
                groupId: event.groupId,
                classNames: event.classNames,
                start: event.start,
                end: event.end,
                allDay: event.allDay,
                specific: specific
            });
        }
    } else {
        formatEvents.push({
            id: events.id,
            groupId: events.groupId,
            classNames: events.classNames,
            start: events.start,
            end: events.end,
            allDay: events.allDay,
            specific: specific
        });   
    }

    const data = JSON.stringify({
        ...formatEvents
    });

    const callback = (function (response) {
        if (!isEmpty(response)) {
            //TODO better feedback
            console.log(response);
        }
    });

    $.ajax({
        type: 'POST',
        data: { availability: data },
        url: base_url + 'settings/json_'+action+'_availability',
        dataType: 'json',
        error: function (xhr, errorType, exception) {
            if (xhr.status && xhr.status == 400) {
                alert(xhr.responseText);
            } else {
                //TODO better error handling
                alert(globalLang.something_went_wrong);
            }
        },
        success: callback
    });
}