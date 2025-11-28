// Calendar Module - Complete Fixed Version
(function() {
    'use strict';

    let calendar;
    let allEvents = [];
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Initialize everything once DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        initializeSidebar();
        initializeCalendar();
        attachEventListeners();
        autoHideFlashMessages();
    });

    /**
     * SIDEBAR TOGGLE - Handles collapsible sidebar
     */
    function initializeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const text = document.getElementById('text');
        const calendarContainer = document.querySelector('.calendar-container');

        if (!sidebar || !toggleBtn) {
            console.warn('Sidebar elements not found');
            return;
        }

        let isCollapsed = false;

        sidebar.style.transition = 'width 0.3s ease-in-out';
        sidebar.style.width = '300px';
        sidebar.style.flexShrink = '0';

        if (calendarContainer) {
            calendarContainer.style.transition = 'flex 0.3s ease-in-out';
            calendarContainer.style.flex = '1';
        }

        toggleBtn.addEventListener('click', function() {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.style.width = '42px';
                if (text) text.style.display = 'none';
            } else {
                sidebar.style.width = '300px';
                if (text) text.style.display = 'block';
            }

            setTimeout(() => {
                if (calendar && typeof calendar.updateSize === 'function') {
                    calendar.updateSize();
                }
            }, 350);
        });
    }

    /**
     * INITIALIZE FULLCALENDAR
     */
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl || typeof FullCalendar === 'undefined') {
            console.error('Calendar element or FullCalendar library not found');
            return;
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: { 
                left: 'prev,next today', 
                center: 'title', 
                right: 'dayGridMonth,dayGridWeek' 
            },
            timeZone: 'local',
            
            // Load events from server
            events: function(info, successCallback, failureCallback) {
                fetch('/calendar/events', { 
                    method: 'GET',
                    headers: { 
                        'X-CSRF-TOKEN': CSRF_TOKEN, 
                        'Accept': 'application/json' 
                    } 
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        allEvents = data.data;
                        successCallback(data.data);
                        updateSidebarForCurrentMonth();
                        console.log('✅ Loaded', data.data.length, 'events from server');
                    } else {
                        throw new Error(data.message || 'Failed to load events');
                    }
                })
                .catch(err => {
                    console.error('❌ Load events error:', err);
                    showNotification('Failed to load calendar events', 'danger');
                    failureCallback(err);
                });
            },
            
            eventClick: function(info) {
                const eventType = info.event.extendedProps.type;
                const eventId = info.event.id;
                
                if (confirm(`Delete this ${eventType}?`)) {
                    if (eventType === 'holiday') {
                        deleteHoliday(eventId);
                    } else if (eventType === 'test') {
                        deleteTest(eventId);
                    }
                }
            },
            
            datesSet: function() {
                updateSidebarForCurrentMonth();
            },
            
            height: 'auto',
            themeSystem: 'standard'
        });

        calendar.render();
        window.calendar = calendar;
    }

    /**
     * ATTACH EVENT LISTENERS FOR BUTTONS AND FORMS
     */
    function attachEventListeners() {
        // Modal buttons
        const addHolidayBtn = document.getElementById('addHolidayBtn');
        const addTestBtn = document.getElementById('addTestBtn');
        const markSundayBtn = document.getElementById('markAllSundayBtn');

        if (addHolidayBtn) {
            addHolidayBtn.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('addHolidayModal'));
                modal.show();
            });
        }

        if (addTestBtn) {
            addTestBtn.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('addTestModal'));
                modal.show();
            });
        }

        if (markSundayBtn) {
            markSundayBtn.addEventListener('click', markAllSundays);
        }

        // Form submissions
        const holidayForm = document.getElementById('holidayForm');
        if (holidayForm) {
            holidayForm.addEventListener('submit', e => handleFormSubmit(e, 'holiday'));
        }

        const testForm = document.getElementById('testForm');
        if (testForm) {
            testForm.addEventListener('submit', e => handleFormSubmit(e, 'test'));
        }
    }

    /**
     * HANDLE FORM SUBMISSION (Holiday/Test)
     */
    function handleFormSubmit(e, type) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        const data = type === 'holiday' 
            ? { 
                date: formData.get('holiday_date'), 
                description: formData.get('holiday_description') 
              }
            : { 
                date: formData.get('test_date'), 
                description: formData.get('test_name') 
              };

        const url = type === 'holiday' ? '/calendar/holidays' : '/calendar/tests';

        fetch(url, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                showNotification(`${capitalize(type)} added successfully`, 'success');
                
                // Close modal
                const modalEl = form.closest('.modal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                form.reset();
                
                // Refresh calendar
                refreshCalendar();
            } else {
                showNotification(res.message || `Failed to add ${type}`, 'danger');
            }
        })
        .catch(err => { 
            console.error('Submit error:', err); 
            showNotification(`Error adding ${type}`, 'danger'); 
        });
    }

    /**
     * DELETE HOLIDAY
     */
    function deleteHoliday(id) {
        fetch(`/calendar/holidays/${id}`, { 
            method: 'DELETE', 
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Accept': 'application/json' 
            } 
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('Holiday deleted successfully', 'success');
                refreshCalendar();
            } else {
                showNotification(data.message || 'Failed to delete holiday', 'danger');
            }
        })
        .catch(err => { 
            console.error('Delete error:', err); 
            showNotification('Error deleting holiday', 'danger'); 
        });
    }

    /**
     * DELETE TEST
     */
    function deleteTest(id) {
        fetch(`/calendar/tests/${id}`, { 
            method: 'DELETE', 
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Accept': 'application/json' 
            } 
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('Test deleted successfully', 'success');
                refreshCalendar();
            } else {
                showNotification(data.message || 'Failed to delete test', 'danger');
            }
        })
        .catch(err => { 
            console.error('Delete error:', err); 
            showNotification('Error deleting test', 'danger'); 
        });
    }

    /**
     * MARK ALL SUNDAYS AS HOLIDAYS
     */
    function markAllSundays() {
        if (!calendar) return;

        const currentDate = calendar.getDate();
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1; // JavaScript months are 0-indexed

        showNotification('Marking all Sundays as holidays...', 'info');

        fetch('/calendar/mark-sundays', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN, 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ year, month })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                refreshCalendar();
            } else {
                showNotification(data.message || 'Failed to mark Sundays', 'danger');
            }
        })
        .catch(err => { 
            console.error('Mark Sundays error:', err); 
            showNotification('Error marking Sundays', 'danger'); 
        });
    }

    /**
     * REFRESH CALENDAR EVENTS
     */
    function refreshCalendar() {
        if (calendar && typeof calendar.refetchEvents === 'function') {
            calendar.refetchEvents();
            console.log('🔄 Calendar refreshed');
        }
    }

    /**
     * GET CURRENT MONTH RANGE
     */
    function getCurrentMonthRange() {
        if (!calendar) return null;
        
        const currentDate = calendar.getDate();
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        return { 
            startDate: new Date(year, month, 1),
            endDate: new Date(year, month + 1, 0),
            month, 
            year 
        };
    }

    /**
     * CHECK IF DATE IS IN RANGE
     */
    function isDateInRange(dateStr, startDate, endDate) {
        const eventDate = new Date(dateStr);
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        eventDate.setHours(0, 0, 0, 0);
        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);
        
        return eventDate >= start && eventDate <= end;
    }

    /**
     * UPDATE SIDEBAR LISTS FOR CURRENT MONTH
     */
    function updateSidebarForCurrentMonth() {
        const range = getCurrentMonthRange();
        if (!range) return;

        const { startDate, endDate } = range;

        const holidays = allEvents
            .filter(e => e.type === 'holiday' && isDateInRange(e.start, startDate, endDate))
            .sort((a, b) => new Date(a.start) - new Date(b.start));

        const tests = allEvents
            .filter(e => e.type === 'test' && isDateInRange(e.start, startDate, endDate))
            .sort((a, b) => new Date(a.start) - new Date(b.start));

        renderList('holiday', holidays);
        renderList('test', tests);
    }

    /**
     * RENDER SIDEBAR LIST
     */
    function renderList(type, items) {
        const listEl = document.getElementById(`${type}List`);
        if (!listEl) return;

        if (items.length === 0) {
            listEl.innerHTML = `<div class="list-item-empty">No ${type}s this month</div>`;
            return;
        }

        listEl.innerHTML = items.map(item => `
            <div class="list-item">
                <div>
                    <div class="list-item-date">${formatDate(item.start)}</div>
                    <div class="list-item-desc">${item.title}</div>
                </div>
                <div class="list-item-actions">
                    <button class="btn-action btn-delete" onclick="window.handleDelete${capitalize(type)}('${item.id}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * EXPOSE DELETE FUNCTIONS GLOBALLY
     */
    window.handleDeleteHoliday = function(id) {
        if (confirm('Are you sure you want to delete this holiday?')) {
            deleteHoliday(id);
        }
    };

    window.handleDeleteTest = function(id) {
        if (confirm('Are you sure you want to delete this test?')) {
            deleteTest(id);
        }
    };

    /**
     * FORMAT DATE FOR DISPLAY
     */
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    }

    /**
     * SHOW NOTIFICATION
     */
    function showNotification(message, type = 'info') {
        let container = document.querySelector('.flash-container');
        
        if (!container) {
            container = document.createElement('div');
            container.className = 'flash-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        container.appendChild(alert);
        
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }

    /**
     * AUTO-HIDE FLASH MESSAGES
     */
    function autoHideFlashMessages() {
        setTimeout(() => {
            document.querySelectorAll('.flash-container .alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    }

    /**
     * CAPITALIZE FIRST LETTER
     */
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    /**
     * WINDOW RESIZE HANDLER FOR CALENDAR
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (calendar && typeof calendar.updateSize === 'function') {
                calendar.updateSize();
            }
        }, 250);
    });

    // Expose refresh function globally
    window.refreshCalendar = refreshCalendar;

})();