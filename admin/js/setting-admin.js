const loadSlotEvent = new CustomEvent("loadSlotEvent");

const getTheMainSelectConainer = document.querySelector(".main_wraper"); // get the main select container
const getTheSelectDateFild = getTheMainSelectConainer.querySelector('.custom_date_picker .custom_date[type="date"][name="custom_date"]'); // get the select date field
const modalOpenBtns = {
    addSlotBtns: getTheMainSelectConainer.querySelectorAll(".add_time_slot"),
    editSlotBtns: getTheMainSelectConainer.querySelectorAll(".edit_btn"),
    offDayBtns: getTheMainSelectConainer.querySelectorAll(".mark_off_day"),
}

const getModalfilds = {
    startTime: getTheMainSelectConainer.querySelector(".picker_modal .start_time_input"),
    endTime: getTheMainSelectConainer.querySelector(".picker_modal .end_time_input"),
    offDayReason: getTheMainSelectConainer.querySelector(".picker_modal .off_day_reason_input"),
    errorMessage: getTheMainSelectConainer.querySelector(".picker_modal .error_message"),
}

const getTitlePlaces = [
    getTheMainSelectConainer.querySelector(".col_heading .main_title"),
    getTheMainSelectConainer.querySelector(".col_heading .sub_title > span"),
    getTheMainSelectConainer.querySelector(".picker_modal .add_slot_title > span"),
    getTheMainSelectConainer.querySelector(".picker_modal .off_day_title > span"),
    getTheMainSelectConainer.querySelector(".custom_date_picker .selected_date p"),
];


const tabStatus = {
    weeklySchedule: "weekly_schedule",
    customDate: "custom_date",
}


getTheMainSelectConainer.querySelector(".weekly_schedule_tab_btn").addEventListener("click", changeTheTab.bind(null, tabStatus.weeklySchedule));
getTheMainSelectConainer.querySelector(".custom_date_tab_btn").addEventListener("click", changeTheTab.bind(null, tabStatus.customDate));
getTheMainSelectConainer.querySelectorAll(".week_days .week_day").forEach((eachWeekDay) => {
    eachWeekDay.addEventListener("change", () => eachWeekDay.checked && setTheSelectedWeekDay());
});
getTheSelectDateFild.addEventListener("change", setTheSelectedCustomDate);



// change the tab based on the selected option
function changeTheTab(selectTab = tabStatus.weeklySchedule) {
    if(selectTab === tabStatus.weeklySchedule) setTheSelectedWeekDay();
    if(selectTab === tabStatus.customDate) setTheSelectedCustomDate();
    getTheMainSelectConainer.setAttribute("selected_tab", selectTab);
}
window.addEventListener("DOMContentLoaded", function(){
    changeTheTab();
}); // set the initial tab

// get the selected week day
function setTheSelectedWeekDay(){
    let checkedTitle = getTheMainSelectConainer.querySelector(".week_days .week_day:checked")?.value;

    if(!checkedTitle) {
        checkedTitle = "monday"; // default to monday if no day is selected
        getTheMainSelectConainer.querySelector(`.week_days .week_day[value="${checkedTitle}"]`).checked = true; // ensure monday is checked
    };
    
    // update the main container attribute
    getTheMainSelectConainer.setAttribute("selected_week_day", checkedTitle);

    getTitlePlaces.forEach((titlePlace) => {
        titlePlace.innerHTML = checkedTitle.replace(/\b\w/g, char => char.toUpperCase());
    });
    
    getTheMainSelectConainer.dispatchEvent(loadSlotEvent); // dispatch the event to load the slots for the selected day
    return checkedTitle;
}; 

// set the selected custom date
function setTheSelectedCustomDate(){
    let getTheSelectedCustomDate = getTheSelectDateFild.value;
    getTheMainSelectConainer.setAttribute("selected_custom_date", getTheSelectedCustomDate)
    if(!getTheSelectedCustomDate) return;

    getTitlePlaces.forEach((titlePlace) => {
        titlePlace.innerHTML = new Date(getTheSelectDateFild.value).toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric", 
            month: "long",
            day: "numeric" 
        });
    });

    getTheMainSelectConainer.dispatchEvent(loadSlotEvent); // dispatch the event to load the slots for the selected day
    return getTheSelectedCustomDate;
};

// Model Open functiuon
function openModal(btnType, slotId = null, startTime = null, endTime = null) {
    if(btnType === "addSlotBtns"){
        getModalfilds.startTime.value = "";
        getModalfilds.endTime.value = "";
        getTheMainSelectConainer.setAttribute("modal_status", "add slot");
    }else if(btnType === "editSlotBtns" && slotId && startTime && endTime){
        getModalfilds.startTime.value = startTime;
        getModalfilds.endTime.value = endTime;
        getTheMainSelectConainer.setAttribute("modal_status", "edit slot");
    }else if(btnType === "offDayBtns"){
        getModalfilds.offDayReason.value = "";
        getTheMainSelectConainer.setAttribute("modal_status", "off day");
    }else{
        getTheMainSelectConainer.setAttribute("modal_status", "false");
    }
}

// Add event listeners to the modal open buttons
for(let btnType in modalOpenBtns){
    modalOpenBtns[btnType].forEach((eachBtn) => {
        eachBtn.addEventListener("click", openModal.bind(null, btnType));
    })
}
// close modal on clicking the close button
getTheMainSelectConainer.querySelectorAll(".picker_modal .close_icon, .picker_modal .cancel_btn").forEach((closeItem) => {
    closeItem.addEventListener("click", function(event){
        getTheMainSelectConainer.setAttribute("modal_status", "false");
    });
});

getTheMainSelectConainer.addEventListener("loadSlotEvent", function(){
    let selectedTab = getTheMainSelectConainer.getAttribute("selected_tab");
    if(selectedTab === tabStatus.weeklySchedule){
        console.log("Loading slots for the selected weekday...");
    }else if(selectedTab === tabStatus.customDate){
        console.log("Loading slots for the selected custom date...");
    };
});

// Hide error message on input change
function hideErrorMessage() {
    getModalfilds.errorMessage.setAttribute("show_status", "false");
    getModalfilds.errorMessage.innerHTML = "";
}
getModalfilds.startTime.addEventListener("input", hideErrorMessage);
getModalfilds.endTime.addEventListener("input", hideErrorMessage);
getModalfilds.offDayReason.addEventListener("input", hideErrorMessage); 

// --------------------------

// Function to connect to the API
async function connectApi(url, data) {
    let option = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": bksh_admin.nonce // Localized nonce from PHP
        },
        credentials: 'same-origin',
        body: JSON.stringify(data)
    };

    try{
        const res = await fetch(url, option);
        const responseData = await res.json();
        return responseData;
    }catch(error) {
        console.error("Error connecting to API:", error);
        return null;
    }
}


// add the time slot
function addTimeSlot(){
    let startTime = getModalfilds.startTime.value;
    let endTime = getModalfilds.endTime.value;
    
    // Validate the start and end time inputs
    if(!startTime && !endTime){
        getModalfilds.errorMessage.setAttribute("show_status", "true");
        getModalfilds.errorMessage.innerHTML = "Please fill in both start and end time.";
        return;
    }else if(!startTime){
        getModalfilds.errorMessage.setAttribute("show_status", "true");
        getModalfilds.errorMessage.innerHTML = "Please fill in the start time.";
        return;
    }else if(!endTime){
        getModalfilds.errorMessage.setAttribute("show_status", "true");
        getModalfilds.errorMessage.innerHTML = "Please fill in the end time.";
        return;
    }else if(startTime >= endTime){
        getModalfilds.errorMessage.setAttribute("show_status", "true");
        getModalfilds.errorMessage.innerHTML = "Start time must be earlier than end time.";
        return;
    }

    const slotType = getTheMainSelectConainer.getAttribute("selected_tab");
    let dateOrDate;
    if(slotType === tabStatus.weeklySchedule){
        dateOrDate = getTheMainSelectConainer.getAttribute("selected_week_day");
    }else if(slotType === tabStatus.customDate){
        dateOrDate = getTheMainSelectConainer.getAttribute("selected_custom_date");
    }
    const slotData = {
        slot_type: slotType,
        date_or_day: dateOrDate,
        start_time: startTime,
        end_time: endTime
    };
    connectApi(bksh_admin.api_insert_url, slotData).then((response) => {
        console.log("Slot added successfully:", response);
        getTheMainSelectConainer.setAttribute("modal_status", "false");
        getTheMainSelectConainer.dispatchEvent(loadSlotEvent);
    });
}
getTheMainSelectConainer.querySelector(".picker_modal .save_btn").addEventListener("click", addTimeSlot);

// edit the time slot
function editTimeSlot(){
    const slotData = {
        slot_id: window.editAbleSlotId,
        start_time: getModalfilds.startTime.value,
        end_time: getModalfilds.endTime.value
    };

    connectApi(bksh_admin.api_update_url, slotData).then((response) => {
        console.log("Slot Updated successfully:", response);
        getTheMainSelectConainer.setAttribute("modal_status", "false");
        getTheMainSelectConainer.dispatchEvent(loadSlotEvent);
    });
}

getTheMainSelectConainer.querySelector(".picker_modal .update_btn").addEventListener("click", editTimeSlot)

// add the off day
function addOffDay(){
    let offDayReason = getModalfilds.offDayReason.value;
    // Validate the off day reason input
    if(!offDayReason){
        getModalfilds.errorMessage.setAttribute("show_status", "true");
        getModalfilds.errorMessage.innerHTML = "Please provide a reason for marking this day as an off day.";
        return;
    }

    const slotType = getTheMainSelectConainer.getAttribute("selected_tab");
    let dateOrDate;
    if(slotType === tabStatus.weeklySchedule){
        dateOrDate = getTheMainSelectConainer.getAttribute("selected_week_day");
    }else if(slotType === tabStatus.customDate){
        dateOrDate = getTheMainSelectConainer.getAttribute("selected_custom_date");
    }
    
    const offDayData = {
        slot_type: slotType,
        date_or_day: dateOrDate,
        offday_reason: offDayReason
    };
    
    connectApi(bksh_admin.api_add_offday_url, offDayData).then((response) => {
        console.log("Off day added successfully:", response);
        getTheMainSelectConainer.setAttribute("modal_status", "false");
        getTheMainSelectConainer.dispatchEvent(loadSlotEvent);
    });
}
getTheMainSelectConainer.querySelector(".picker_modal .off_day_btn").addEventListener("click", addOffDay);

// Remove off day
getTheMainSelectConainer.querySelector(".off_day_notice .remove_off_day").addEventListener("click", function(){
    const slotType = getTheMainSelectConainer.getAttribute("selected_tab");
    let dateOrDate;
    if(slotType === tabStatus.weeklySchedule){
        dateOrDate = getTheMainSelectConainer.getAttribute("selected_week_day");
    }else if(slotType === tabStatus.customDate){
        dateOrDate = getTheMainSelectConainer.getAttribute("selected_custom_date");
    }
    console.log(dateOrDate);
    connectApi(bksh_admin.api_delete_offday_url, {date_or_day: dateOrDate}).then((response) => {
        console.log("Off Day Remove successfully:", response);
        getTheMainSelectConainer.dispatchEvent(loadSlotEvent);
    });
});


// Load slots
const getTheSlotContainer = getTheMainSelectConainer.querySelector(".time_solts");
const defultSlotTemplate = document.querySelector('#each_time_slot_template').content;
let loadSlotFun = loadSlotAsync(); // Initialize the async function

function loadSlotAsync(){
    let tempForSetTimeout;
    return function(){
        if(tempForSetTimeout) clearTimeout(tempForSetTimeout);
        tempForSetTimeout = setTimeout(async () => {
            const slotType = getTheMainSelectConainer.getAttribute("selected_tab");
            let dateOrDate;
            if(slotType === tabStatus.weeklySchedule){
                dateOrDate = getTheMainSelectConainer.getAttribute("selected_week_day");
            }else if(slotType === tabStatus.customDate){
                dateOrDate = getTheMainSelectConainer.getAttribute("selected_custom_date");
            }

            if(!slotType || !dateOrDate) return console.error("Slot type or date is not selected.");

            const slotData = {
                slot_type: slotType,
                date_or_day: dateOrDate
            };
            const response = await connectApi(bksh_admin.api_get_url, slotData);
            console.log("Slots loaded:", response);
            if(response && response.status === "success") {
                getTheSlotContainer.innerHTML = ""; // Clear existing slots
                getTheMainSelectConainer.setAttribute("off_day_status", response?.offday_status);
                if(response?.offday_status){
                    getTheMainSelectConainer.querySelector(".off_day_notice p.off_day_reason").innerHTML = response?.offday_reason;
                    return;
                }
                response?.slots.sort((a, b) => a.start_time.localeCompare(b.start_time));
                response?.slots.forEach(eachSlot);
            } else {
                console.error("Failed to load slots:", response);
            }
        }, 0);
    }
}
const today = new Date().toISOString().split('T')[0];
function eachSlot(slotData) {
    const eachSlotTemplate =  defultSlotTemplate.cloneNode(true);
    startTimeD = new Date(`${today}T${slotData.start_time}`);
    endTimeD = new Date(`${today}T${slotData.end_time}`);
    const options = { hour: 'numeric', minute: '2-digit', hour12: true };
    eachSlotTemplate.querySelector(".time").innerHTML = `${startTimeD.toLocaleTimeString('en-US', options)} - ${endTimeD.toLocaleTimeString('en-US', options)}`;
    let duration = Math.floor(((endTimeD - startTimeD) / (1000 * 60 * 60)) * 100) / 100; // Calculate duration in hours
    eachSlotTemplate.querySelector(".duration").innerHTML = `${duration} hours`;
    // Edit Functionality
    eachSlotTemplate.querySelector(".edit_btn").addEventListener("click", function(){
        openModal("editSlotBtns", slotData.slot_id, slotData.start_time, slotData.end_time);
        window.editAbleSlotId = slotData.slot_id; // Store the slot ID for editing
    });

    // Delete Functionality
    eachSlotTemplate.querySelector(".delete_btn").addEventListener("click", function(){
        if(confirm("Are you sure you want to delete this slot?")) {
            const deleteData = {
                slot_id: slotData.slot_id
            };
            connectApi(bksh_admin.api_delete_url, deleteData).then((response) => {
                console.log("Slot deleted successfully:", response);
                getTheMainSelectConainer.dispatchEvent(loadSlotEvent);
            });
        }
    });

    getTheSlotContainer.appendChild(eachSlotTemplate);
}

getTheMainSelectConainer.addEventListener("loadSlotEvent", loadSlotFun);

