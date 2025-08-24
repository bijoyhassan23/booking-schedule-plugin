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
                console.log("Date selected:", info);
            }else{
                info.dayEl.removeAttribute("date_status");
                selectedDates = selectedDates.filter(item => item !== info.dateStr);
            }
        },
        dayCellDidMount: function(arg) {
            let yyyy = arg.date.getFullYear();
            let mm = String(arg.date.getMonth() + 1).padStart(2, "0");
            let dd = String(arg.date.getDate()).padStart(2, "0");
            let dateStr = `${yyyy}-${mm}-${dd}`;
            // console.log(dateStr);

            if (arg.date.getTime() < todayDateObj.getTime() || window?.offDayData.includes(dateStr) || window?.offDayData.includes(arg.date.getDay())) {
                // past dates disabled
                arg.el.setAttribute("date_status", "desabled");
            }else if (selectedDates.includes(dateStr)) {
                // re-apply selected style after rerender
                arg.el.setAttribute("date_status", "selected");
            }
        }
    });

    fetch(bksh_frontend.api_get_offdays_url)
    .then(response => response.json())
    .then(data => {
        window.offDayData = data?.offdays;
        calendar.render();
    })
    .catch(error => {
        window.offDayData = [];
        calendar.render();
        console.error("Fetch error:", error);
    });
});