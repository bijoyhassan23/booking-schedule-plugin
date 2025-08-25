// Booking Notice
function bookingNoticeCloser(){
    const noticeDiv = document.createElement("div");
    noticeDiv.classList.add("booking_notice");
    noticeDiv.setAttribute("show_status", false);
    noticeDiv.innerHTML = `
        <div class="notice_container">
            <div class="notice_title">Booking Information</div>
            <p class="notice_message">Booked slots can still be reserved with a $25 extra fee. This allows multiple customers to share the same time slot.</p>
            <div class="button_group">
                <button class="close">Close</button>
                <button class="okay">Okay</button>
            </div>
        </div>
    `;
    document.body.appendChild(noticeDiv);
    let noticeTitle = noticeDiv.querySelector(".notice_title");
    let noticeMessage = noticeDiv.querySelector(".notice_message");
    let noticeOkay = noticeDiv.querySelector(".okay");
    let noticeClose = noticeDiv.querySelector(".close");

    return function ({title = "", message = "" } = {}) {
        noticeTitle.innerHTML = title;
        noticeMessage.innerHTML = title;
        noticeDiv.setAttribute("show_status", true);

        return new Promise((res, rej) => {
            noticeOkay.addEventListener('click', function(){
                noticeDiv.setAttribute("show_status", false);
                res(true);
            })
            noticeClose.addEventListener('click', function(){
                noticeDiv.setAttribute("show_status", false);
                res(false);
            })
        });
    }
}
let bookingNotice = bookingNoticeCloser();

// celendar Functionality
let selectionLimit = 5;
let selectedDates = [];
const fetchedSlotMemory = {};
const loadSlotEvent = new CustomEvent("loadSlotEvent");
const todayDateObj = new Date();
todayDateObj.setHours(0, 0, 0, 0); // Set time to midnight for comparison

let calendar;
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.querySelector('.slot_calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        dateClick: function(info) {
            if(info.dayEl.getAttribute("date_status") === "desabled") return;

            if(!info.dayEl.getAttribute("date_status") && selectedDates.length < selectionLimit){
                info.dayEl.setAttribute("date_status", "selected");
                selectedDates.push(info.dateStr);
                selectedDates.sort((a, b) => new Date(a) - new Date(b));
            }else{
                info.dayEl.removeAttribute("date_status");
                selectedDates = selectedDates.filter(item => item !== info.dateStr);
            }
            document.dispatchEvent(loadSlotEvent);
        },
        dayCellDidMount: function(arg) {
            let dateStr = arg.date.toISOString().split('T')[0];

            if (arg.date.getTime() < todayDateObj.getTime() || window?.offDayData.includes(dateStr) || window?.offDayData.includes(arg.date.getDay())) {
                // past dates disabled
                arg.el.setAttribute("date_status", "desabled");
            }else if (selectedDates.includes(dateStr)) {
                // re-apply selected style after rerender
                arg.el.setAttribute("date_status", "selected");
            }
        }
    });

    // Get off days and call calendar
    fetch(bksh_frontend.api_get_offdays_url)
    .then(response => response.json())
    .then(data => {
        window.offDayData = data?.offdays;
        calendar.render();
    }).catch(error => {
        window.offDayData = [];
        calendar.render();
        console.error("Fetch error:", error);
    });
});

// load Slot
const getSlotContainer = document.querySelector('.booking_date_picker .slot_time');

function addEachDaySlot(slotInfo){
    if(slotInfo['element']){
        getSlotContainer.appendChild(slotInfo['element']);
        return;
    }

    let dateStr = slotInfo.date.toISOString().split('T')[0];
    let labelRadios = ``;
    const options = { hour: 'numeric', minute: '2-digit', hour12: true };
    slotInfo.slots.forEach(eachTimeSlot => {
        let startTimeD = new Date(`${dateStr}T${eachTimeSlot.start_time}`);
        let endTimeD = new Date(`${dateStr}T${eachTimeSlot.end_time}`);
        let bookedStatus = !!eachTimeSlot.booked;
        labelRadios += `
            <label class="time_slot">
                <input type="radio" class="slot_radio" name="${dateStr}" value="${startTimeD.toLocaleTimeString('en-US', options)} - ${endTimeD.toLocaleTimeString('en-US', options)}" booked="${bookedStatus}">
                <span class="start_end_time">${startTimeD.toLocaleTimeString('en-US', options)} - ${endTimeD.toLocaleTimeString('en-US', options)}</span>
                <div class="slot_status">
                    <span class="available">Available</span>
                    <span class="booked">Booked</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
            </label>
        `;
    })

    const eachDaySlot = document.createElement('div');
    const title = slotInfo.date.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric", 
            month: "long",
            day: "numeric" 
        });
    eachDaySlot.innerHTML = `
        <div class="date_slot_title">${title}</div>
        <div class="date_time_slots">${labelRadios ? labelRadios : "No slot Available on this date"}</div>
    `;
    eachDaySlot.classList.add('each_date_slot');
    slotInfo['element'] = eachDaySlot;
    getSlotContainer.appendChild(eachDaySlot);
}

async function getSlots(date) {
    if(fetchedSlotMemory[date] !== undefined){
        return new Promise(res => res(fetchedSlotMemory[date]));
    }
    try {
        const response = await fetch(`${bksh_frontend.api_get_slots_url}?date=${date}`);
        const data = await response.json();
        fetchedSlotMemory[date] = {
            date: new Date(date+"T00:00:00"),
            slots: data
        };
        return fetchedSlotMemory[date];
    } catch (error) {
        return new Promise((res, rej) => rej(false));
    }
}

function loadSlot(){
    getSlotContainer.innerHTML = "";
    const prmiseTemp = [];
    selectedDates.forEach((item) => {
        prmiseTemp.push(getSlots(item));
    });
    Promise.all(prmiseTemp)
    .then(allSlot => {
        allSlot.forEach(addEachDaySlot);
    })
    .catch((err) =>{
        console.log(err);
    }) 
}

document.addEventListener('loadSlotEvent', loadSlot);