var vanillaCalendar = {
  month: document.querySelectorAll('[data-calendar-area="month"]')[0],
  time: document.querySelectorAll('[time-calendar-area="day"]')[0],
  next: document.querySelectorAll('[data-calendar-toggle="next"]')[0],
  previous: document.querySelectorAll('[data-calendar-toggle="previous"]')[0],
  label: document.querySelectorAll('[data-calendar-label="month"]')[0],
  activeDates: null,
  date: new Date(),
  todaysDate: new Date(),
  appointments: {},
  times: [],
  settings: {},
  datePicked: 'yyyy-mm-dd',
  startTime: '00:00:00',
  endTime: '00:00:00',

  init: function (options) {
    this.options = options
    this.date.setDate(1)
    this.createMonth()
    this.createListeners()
  },

  clickedInit: function (date) {
    const _this = this;
    this.createTimes()
    this.setAppointments(function(response){
      _this.setTimes(function(response){
        _this.setSettings(function(response){
          _this.createTimeButtons(date)
        })
      }, date)
    })
  },

  createListeners: function () {
    var _this = this
    this.next.addEventListener('click', function () {
      _this.clearCalendar()
      var nextMonth = _this.date.getMonth() + 1
      _this.date.setMonth(nextMonth)
      _this.createMonth()
    })
    // Clears the calendar and shows the previous month
    this.previous.addEventListener('click', function () {
      _this.clearCalendar()
      var prevMonth = _this.date.getMonth() - 1
      _this.date.setMonth(prevMonth)
      _this.createMonth()
    })
  },

  createDay: function (num, day, year) {
    var newDay = document.createElement('div')
    var dateEl = document.createElement('span')
    dateEl.innerHTML = num
    newDay.className = 'vcal-date'
    newDay.setAttribute('data-calendar-date', this.date)

    // if it's the first day of the month
    if (num === 1) {
      if (day === 0) {
        newDay.style.marginLeft = (6 * 14.28) + '%'
      } else {
        newDay.style.marginLeft = ((day - 1) * 14.28) + '%'
      }
    }

    if (this.options.disablePastDays && this.date.getTime() <= this.todaysDate.getTime() - 1) {
      newDay.classList.add('vcal-date--disabled')
    } else {
      newDay.classList.add('vcal-date--active')
      newDay.setAttribute('data-calendar-status', 'active')
    }

    if (this.date.toString() === this.todaysDate.toString()) {
      newDay.classList.add('vcal-date--today')
    }

    newDay.appendChild(dateEl)
    this.month.appendChild(newDay)
  },

  dateClicked: function () {
    var _this = this
    this.activeDates = document.querySelectorAll(
      '[data-calendar-status="active"]'
    )
    for (var i = 0; i < this.activeDates.length; i++) {
      this.activeDates[i].addEventListener('click', function (event) {
        document.getElementById('vcal-times').classList.add('hidden');
        var picked = document.querySelectorAll(
          '[data-calendar-label="picked-date"]'
        )[0]
        var date = new Date(this.dataset.calendarDate)
        var day = date.getDate()
        var month = date.getMonth()
        var year = date.getFullYear()
        picked.innerHTML = day + ' ' + _this.monthsAsString(month) + ' ' + year
        _this.removeActiveClass()
        this.classList.add('vcal-date--selected')
        if (!isEmpty(document.getElementById('carousel-next'))) {
          document.getElementById('carousel-next').remove()
        }
        _this.clickedInit(this.dataset.calendarDate)
        //for ajax call
        _this.datePicked = year + '-' + month + '-' + day;
      })
    }
  },

  createMonth: function () {
    var currentMonth = this.date.getMonth()
    while (this.date.getMonth() === currentMonth) {
      this.createDay(
        this.date.getDate(),
        this.date.getDay(),
        this.date.getFullYear()
      )
      this.date.setDate(this.date.getDate() + 1)
    }
    // while loop trips over and day is at 30/31, bring it back
    this.date.setDate(1)
    this.date.setMonth(this.date.getMonth() - 1)

    this.label.innerHTML =
      this.monthsAsString(this.date.getMonth()) + ' ' + this.date.getFullYear()
    this.dateClicked()
  },

  monthsAsString: function (monthIndex) {
    //TODO also in dutch
    return [
      globalLang.january,
      globalLang.febuary,
      globalLang.march,
      globalLang.april,
      globalLang.may,
      globalLang.june,
      globalLang.july,
      globalLang.august,
      globalLang.september,
      globalLang.october,
      globalLang.november,
      globalLang.december
    ][monthIndex]
  },

  clearCalendar: function () {
    vanillaCalendar.month.innerHTML = ''
  },

  removeActiveClass: function () {
    for (var i = 0; i < this.activeDates.length; i++) {
      this.activeDates[i].classList.remove('vcal-date--selected')
    }
  },

  setAppointments: function (callback) {
    let _this = this;

    $.ajax({
      type: 'GET',
      url: base_url + 'appointment/json_get_appointments/' + cs_username + '/' + eventTitle,
      dataType: 'json',
      error: function (xhr, errorType, exception) {
        if (xhr.status && xhr.status == 400) {
          alert(xhr.responseText);
        } else {
          //TODO better error handling
          alert(globalLang.something_went_wrong);
        }
      },
      success: function (response) {
        if (!isEmpty(response)) {
          if (response.executed === true) {
            _this.appointments = response.success;
          } else {
            document.getElementById("accordion").remove();
            alert(response.error);
          }
        }
        callback(_this.settings);
      }
    });
  },

  setSettings: function (callback) {
    let _this = this;

    $.ajax({
      type: 'GET',
      url: base_url + 'appointment/json_get_settings/' + cs_username,
      dataType: 'json',
      error: function (xhr, errorType, exception) {
        if (xhr.status && xhr.status == 400) {
          alert(xhr.responseText);
        } else {
          //TODO better error handling
          alert(globalLang.something_went_wrong);
        }
      },
      success: function (response) {
        if (!isEmpty(response)) {
          if (response.executed === true) {
            _this.settings = response.success;
          } else {
            document.getElementById("accordion").remove();
            alert(response.error);
          }
        }
        callback(_this.settings);
      }
    });
  },

  setTimes: function (callback, clickedDate) {
    const _this = this;

    $.ajax({
      type: 'GET',
      url: base_url + 'appointment/json_get_times/' + cs_username,
      dataType: 'json',
      error: function (xhr, errorType, exception) {
        if (xhr.status && xhr.status == 400) {
          alert(xhr.responseText);
        } else {
          //TODO better error handling
          alert(globalLang.something_went_wrong);
        }
      },
      success: function (response) {
        if (!isEmpty(response)) {
          if (response.executed === true) {
            _this.times = [];
            for (const key in response.success) {
              const dates = response.success[key];
              const start_date = dates.start.split(' ')[0];
              const start_time = dates.start.split(' ')[1];
              const end_date = dates.end.split(' ')[0];
              const end_time = dates.end.split(' ')[1];
              if (start_date.length > 1) {
                if (new Date(start_date).getDate() === new Date(clickedDate).getDate()) {
                  if (start_date === end_date) {
                    _this.times.push({ start: start_time, end: end_time });
                  } else {
                    //TODO multiple days
                    alert('multiple days needs to be coded. Work in progress');
                  }
                }
              } else {
                if (parseInt(start_date) === new Date(clickedDate).getDay()) {
                  if (start_date === end_date) {
                    _this.times.push({ start: start_time, end: end_time });
                  } else {
                    //TODO multiple days
                    alert('multiple days needs to be coded. Work in progress');
                  }
                }
              }
            }
          } else {
            document.getElementById("accordion").remove();
            alert(response.error);
          }
        }
        callback(_this.times);
      }
    });
  },

  //custom functions
  createTimes: function () {
    //remove existing elements if exist
    if (document.contains(document.getElementById("accordion"))) {
      document.getElementById("accordion").remove();
    }
    if (!isEmpty(document.getElementById('no-times'))) {
      document.getElementById('no-times').remove();
    }
    const today = new Date();
    const timeNow = today.getTime();
    const moments = {
      'morning': {
        'start': new Date(timeNow).setHours(6, 0, 0),
        'end': new Date(timeNow).setHours(11, 59, 59)
      },
      'afternoon': {
        'start': new Date(timeNow).setHours(12, 0, 0),
        'end': new Date(timeNow).setHours(16, 59, 59)
      },
      'evening': {
        'start': new Date(timeNow).setHours(17, 0, 00),
        'end': new Date(timeNow).setHours(22, 0, 0)
      }
    };

    let accordion = document.createElement("DIV");

    setAttributes(accordion, {
      id: 'accordion'
    });

    for (const [moment, times] of Object.entries(moments)) {
      let expanded = false;
      if (timeNow >= times['start'] && timeNow <= times['end']) {
        expanded = true;
      }
      
      let card = document.createElement("DIV");
      let cardHeader = document.createElement("BUTTON");
      let cardHeaderInfo = document.createElement("SMALL");
      let cardBody = document.createElement("DIV");
      let collapse = document.createElement("DIV");

      setAttributes(card, {
        id: moment,
        class: 'card'
      });
      setAttributes(cardHeader, {
        id: 'header-' + moment,
        class: 'card-header btn btn-link',
        data_toggle: 'collapse',
        data_target: '#collapse-' + moment,
        aria_expanded: expanded.toString(),
        aria_controls: "collapse-" + moment
      });
      setAttributes(cardHeaderInfo, {
        class: 'info-list-item text-muted'
      });
      setAttributes(cardBody, {
        id: 'body-' + moment,
        class: 'card-body text-center'
      });
      setAttributes(collapse, {
        id: "collapse-" + moment,
        class: "collapse",
        aria_labelledby: "heading-" + moment,
        data_parent: "#accordion"
      });

      if (expanded === true) {
        collapse.classList.add('show');
      }

      cardHeaderInfo.innerHTML = globalLang.click_me;
      if (!isEmpty(globalLang[moment])) {
        cardHeader.innerHTML = toFirstUpperCase(globalLang[moment]);
      } else {
        cardHeader.innerHTML = toFirstUpperCase(moment);
      }

      collapse.appendChild(cardBody);
      cardHeader.appendChild(cardHeaderInfo);

      card.appendChild(cardHeader);
      card.appendChild(collapse);

      accordion.appendChild(card);
    }

    vanillaCalendar.time.appendChild(accordion);
  },

  createTimeButtons: function(clickedDate) {
    const _this = this;
    const today = new Date();
    const clicked = new Date(clickedDate);
    const max = parseInt(this.settings.appointments_a_day);
    let maxCount = 0;

    let timeSlots = this.setTimeSlots();
    let create = false;
    //check if body is filled. Otherwise remove body
    let bodyMorning = false;
    let bodyAfternoon = false;
    let bodyEvening = false;

    let checkDate = clicked.getFullYear() + '-' + clicked.getMonth() + '-' + clicked.getDate();
    
    //remove from time slots if appointments are made
    if (!isEmpty(this.appointments)) {
      const interimHours = parseInt(this.settings.appointment_interim.split(':')[0]);
      const interimMinutes = parseInt(this.settings.appointment_interim.split(':')[1]);
      const interimSeconds = parseInt(this.settings.appointment_interim.split(':')[2]); //not used
      for (let index = 0; index < this.appointments.length; index++) {
        const appointment = this.appointments[index];
        if (checkDate === appointment.date) {
          maxCount++;
          if (maxCount > max) {
            this.noTimeMessage();
          }
          const startTimeHours = parseInt(appointment.start_time.split(':')[0]);
          const startTimeMinutes = parseInt(appointment.start_time.split(':')[1]);
          const startTimeSeconds = parseInt(appointment.start_time.split(':')[2]); //not used
          const appointmentStart = startTimeHours + (startTimeMinutes / 60);
          
          const endTimeHours = parseInt(appointment.end_time.split(':')[0]);
          const endTimeMinutes = parseInt(appointment.end_time.split(':')[1]);
          const endTimeSeconds = parseInt(appointment.end_time.split(':')[2]); //not used
          let appointmentEnd = endTimeHours + interimHours;

          if (endTimeMinutes + interimMinutes >= 60) {
            appointmentEnd += 1 + ((endTimeMinutes + interimMinutes - 60) / 60) 
          } else {
            appointmentEnd += (endTimeMinutes / 60) + (interimMinutes / 60)
          }

          for (const [key, time] of Object.entries(timeSlots)) {
            const startTimeSlotHours = parseInt(time.start.split(':')[0]);
            const startTimeSlotMinutes = parseInt(time.start.split(':')[1]);
            const startTimeSlotSeconds = parseInt(time.start.split(':')[2]); //not used
            const timeSlotStart = startTimeSlotHours + (startTimeSlotMinutes / 60);

            const endTimeSlotHours = parseInt(time.end.split(':')[0]);
            const endTimeSlotMinutes = parseInt(time.end.split(':')[1]);
            const endTimeSlotSeconds = parseInt(time.end.split(':')[2]); //not used
            const timeSlotEnd = endTimeSlotHours + (endTimeSlotMinutes / 60);

            if (appointmentStart <= timeSlotStart && appointmentEnd >= timeSlotEnd) {
              delete timeSlots[key]
            }
          }
        }
      }
    }

    if (maxCount <= max) {
      timeSlots.forEach(time => {
        let timeButton = document.createElement("BUTTON");
        let innerStart = time.start.split(':')[0] + ':' + time.start.split(':')[1];
        let innerEnd = time.end.split(':')[0] + ':' + time.end.split(':')[1];
        const checkTime = parseFloat(parseInt(time.start.split(':')[0]) + parseFloat(time.start.split(':')[1] / 60));

        setAttributes(timeButton, {
          class: 'btn btn-outline-dark'
        });

        timeButton.addEventListener('click', function () {
          if (isEmpty(document.getElementById('carousel-next'))) {
            _this.createNextButton()
          }
          let focusedButtons = document.querySelectorAll('.btn-focused');
          if (!isEmpty(focusedButtons)) {
            [].forEach.call(focusedButtons, function (button) {
              button.classList.remove("btn-focused");
            });
          }
          this.classList.add('btn-focused');
          let picked = document.querySelectorAll(
            '[data-calendar-label="picked-time"]'
          )[0];
          picked.innerHTML = ' ' + globalLang.at + ' ' + innerStart + ' - ' + innerEnd;
          //for ajax call
          _this.startTime = time.start + ':00';
          _this.endTime = time.end + ':00';
        });

        timeButton.innerHTML = innerStart + ' - ' + innerEnd;

        if (checkTime > today.getHours() + 4 && new Date(clickedDate).getDate() === today.getDate()) {
          create = true;
        } else if (new Date(clickedDate).getDate() !== today.getDate()) {
          create = true;
        }

        if (create === true) {
          if (checkTime < 12) {
            document.getElementById('body-morning').appendChild(timeButton);
            bodyMorning = true;
          } else if (checkTime >= 12 && checkTime < 17) {
            document.getElementById('body-afternoon').appendChild(timeButton);
            bodyAfternoon = true;
          } else {
            document.getElementById('body-evening').appendChild(timeButton);
            bodyEvening = true;
          }
        }
      });

      if (bodyMorning === false) {
        if (!isEmpty(document.getElementById('collapse-morning'))) {
          if (document.getElementById('collapse-morning').classList.contains('show')) {
            //make sure an other collapse is shown if this collapse has no times
            if (bodyAfternoon !== false) {
              document.getElementById('collapse-afternoon').classList.add('show');
            } else if (bodyEvening !== false) {
              document.getElementById('collapse-evening').classList.add('show');
            }
          }
          document.getElementById('morning').remove();
        }
      }
      if (bodyAfternoon === false) {
        if (!isEmpty(document.getElementById('collapse-afternoon'))) {
          if (document.getElementById('collapse-afternoon').classList.contains('show')) {
            //make sure an other collapse is shown if this collapse has no times
            if (bodyMorning !== false) {
              document.getElementById('collapse-morning').classList.add('show');
            } else if (bodyEvening !== false) {
              document.getElementById('collapse-evening').classList.add('show');
            }
          }
          document.getElementById('afternoon').remove();
        }
      }
      if (bodyEvening === false) {
        if (!isEmpty(document.getElementById('collapse-evening'))) {
          if (document.getElementById('collapse-evening').classList.contains('show')) {
            //make sure an other collapse is shown if this collapse has no times
            if (bodyMorning !== false) {
              document.getElementById('collapse-morning').classList.add('show');
            } else if (bodyAfternoon !== false) {
              document.getElementById('collapse-afternoon').classList.add('show');
            }
          }
          document.getElementById('evening').remove();
        }
      }
      if (bodyMorning === false && bodyAfternoon === false && bodyEvening === false) {
        this.noTimeMessage();
      }
    }
    document.getElementById('vcal-times-box').classList.remove('hidden');
    document.getElementById('vcal-times').classList.remove('hidden');
  },

  createNextButton: function () {
    const _this = this;
    let button = document.createElement("BUTTON");
    let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

    setAttributes(button, {
      id: 'carousel-next',
      class: 'carousel-control-next btn btn-primary',
      href: '#carouselControls',
      role: 'button',
      data_slide: 'next'
    });

    setAttributes(svg, {
      class: 'arrow-icon',
      height: '24',
      version: '1.1',
      viewbox: '0 0 24 24',
      width: '24',
      xmlns: 'http://www.w3.org/2000/svg'
    });

    path.setAttributeNS(null, 'd', 'M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z');

    if (!isEmpty(globalLang.next)) {
      button.innerHTML = toFirstUpperCase(globalLang.next);
    } else {
      button.innerHTML = toFirstUpperCase('next');
    }

    svg.appendChild(path);
    button.appendChild(svg);

    setTimeout(() => {
      document.getElementById('carousel-nav-bottom').appendChild(button);
    }, 300);

    button.addEventListener('click', function () {
      this.remove()
      document.getElementById('header-step-1').classList.add('hidden')
      document.getElementById('header-step-2').classList.remove('hidden')
      document.getElementById('progress-bar').classList.remove('progress-bar--animation__from-full')
      document.getElementById('progress-bar').classList.remove('progress-bar--animation__to-half')
      document.getElementById('progress-bar').classList.add('progress-bar--animation__to-full')
      document.getElementById('progress-bar').setAttribute('aria-valuenow', '100')
      _this.createPrevButton()
      if (!isEmpty(document.getElementById('submit'))) {
        setTimeout(() => {
          document.getElementById('submit').classList.remove('hidden')
        }, 300);
      }
      if ($('#step-2 #appointment-form').length === 0) {
        //TODO make name dynamic
        makeForm('appointment');
      }
    });
  },

  createPrevButton: function () {
    const _this = this;
    let button = document.createElement("A");
    let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

    setAttributes(button, {
      id: 'carousel-prev',
      class: 'carousel-control-prev',
      href: '#carouselControls',
      role: 'button',
      data_slide: 'prev'
    });

    setAttributes(svg, {
      class: 'arrow-icon',
      height: '24',
      version: '1.1',
      viewbox: '0 0 24 24',
      width: '24',
      xmlns: 'http://www.w3.org/2000/svg'
    });

    if (!isEmpty(globalLang.previous)) {
      button.innerHTML = toFirstUpperCase(globalLang.previous);
    } else {
      button.innerHTML = toFirstUpperCase('previous');
    }

    path.setAttributeNS(null, 'd', 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z');

    svg.appendChild(path);
    button.prepend(svg);

    setTimeout(() => {
      document.getElementById('carousel-nav-top').appendChild(button);
    }, 300);

    button.addEventListener('click', function () {
      this.remove()
      document.getElementById('header-step-2').classList.add('hidden')
      document.getElementById('header-step-1').classList.remove('hidden')
      document.getElementById('progress-bar').classList.remove('progress-bar--animation__to-full')
      document.getElementById('progress-bar').classList.add('progress-bar--animation__from-full')
      document.getElementById('progress-bar').setAttribute('aria-valuenow', '50')
      document.getElementById('submit').classList.add('hidden')
      _this.createNextButton()
    });
  },

  setTimeSlots: function () {
    let timeSlots = [];

    const startTimes = this.settings.appointment_start_times.replace('[', '').replace(']', '').split(',');
    const timeZone = this.settings.time_zone;

    if (!isEmpty(this.times)) {
      for (let index = 0; index < this.times.length; index++) {
        const time = this.times[index];

        //start times
        const startHours = parseInt(time.start.split(':')[0]);
        const startMinutes = parseInt(time.start.split(':')[1]);
        const startSeconds = parseInt(time.start.split(':')[2]); //not used
        const start = parseFloat(startHours + (startMinutes / 60));

        //end times
        const endHours = parseInt(time.end.split(':')[0]);
        const endMinutes = parseInt(time.end.split(':')[1]);
        const endSeconds = parseInt(time.end.split(':')[2]); //not used
        const end = parseFloat(endHours + (endMinutes / 60));

        //duration {eventDuration} is global
        const durationHours = parseInt(eventDuration.split(':')[0]);
        const durationMinutes = parseInt(eventDuration.split(':')[1]);
        const durationSeconds = parseInt(eventDuration.split(':')[2]); //not used
        const duration = parseFloat(durationHours + (durationMinutes / 60));

        for (let time = start; time + duration <= end; time += duration) {
          timeStartHours = parseInt(Math.floor(time));
          timeStartMinutes = parseInt(Math.ceil((time % 1) * 60));
          if (timeStartMinutes >= 60) {
            timeStartMinutes = timeStartMinutes - 60;
            timeStartHours++;
          }
          timeEndHours = timeStartHours + durationHours;
          timeEndMinutes = timeStartMinutes + durationMinutes;
          if (timeEndMinutes >= 60) {
            timeEndMinutes = timeEndMinutes - 60;
            timeEndHours++;
          }
          startTimes.forEach(startTime => {
            if (timeStartMinutes === parseInt(startTime)) {
              let slot = {
                'start': ("0" + timeStartHours).slice(-2) + ':' + ("0" + timeStartMinutes).slice(-2) + ':00',
                'end': ("0" + timeEndHours).slice(-2) + ':' + ("0" + timeEndMinutes).slice(-2) + ':00'
              };
              if (!timeSlots.includes(slot)) {
                timeSlots.push(slot);
              }
            }
          });
        }
      }
    } else {
      this.noTimeMessage();
    }
    return timeSlots;
  },

  noTimeMessage: function () {
    if (!isEmpty(document.getElementById('accordion'))) {
      document.getElementById('accordion').remove();
    }

    if (isEmpty(document.getElementById('no-times'))) {
      let messageBox = document.createElement("DIV");
      let message = document.createElement("H5");
  
      setAttributes(messageBox, {
        id: 'no-times',
        class: 'alert alert-warning',
        role: 'alert'
      });
      setAttributes(message, {
        class: 'alert-heading'
      });
      
      //TODO translate error message
      message.innerHTML = globalLang.no_times_available;
      messageBox.appendChild(message);
  
      document.getElementById('vcal-times').appendChild(messageBox);
      document.getElementById('vcal-times-box').classList.remove('hidden');
      document.getElementById('vcal-times').classList.remove('hidden');
    }
  }
}