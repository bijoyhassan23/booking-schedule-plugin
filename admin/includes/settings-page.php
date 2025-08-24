<?php

// HTML output for the booking times settings page
function bksh_booking_times_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <div class="main_wraper" selected_tab="weekly schedule" selected_week_day="monday" off_day_status="false" selected_custom_date="" modal_status="flase">
            <div class="slot_selector">

                <div class="col left_col">
                    <div class="col_heading">
                        <div class="page-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                            <h2>Schedule Manager</h2>
                        </div>

                       <div class="sub_title">Select a day to manage</div>
                    </div>

                    <div class="tab_heading">
                        <div class="each_tab_heading weekly_schedule_tab_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                            <span>Weekly Schedule</span>
                        </div>

                        <div class="each_tab_heading custom_date_tab_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                            <span>Custom Dates</span>
                        </div>

                    </div>

                    <div class="tab_realteds">

                        <div class="week_days">

                            <label class="week_day_title">
                                <span>Monday</span>
                                <input type="radio" name="week_day" class="week_day" value="monday" checked>
                            </label>

                            <label class="week_day_title">
                                <span>Tuesday</span>
                                <input type="radio" name="week_day" class="week_day" value="tuesday">
                            </label>

                            <label class="week_day_title">
                                <span>Wednesday</span>
                                <input type="radio" name="week_day" class="week_day" value="wednesday">
                            </label>

                            <label class="week_day_title">
                                <span>Thursday</span>
                                <input type="radio" name="week_day" class="week_day" value="thursday">
                            </label>

                            <label class="week_day_title">
                                <span>Friday</span>
                                <input type="radio" name="week_day" class="week_day" value="friday">
                            </label>

                            <label class="week_day_title">
                                <span>Saturday</span>
                                <input type="radio" name="week_day" class="week_day" value="saturday">
                            </label>

                            <label class="week_day_title">
                                <span>Sunday</span>
                                <input type="radio" name="week_day" class="week_day" value="sunday">
                            </label>
                            
                        </div>

                        <div class="custom_date_picker">
                            <label class="date_picker">
                                <span>Select Date</span>
                                <input type="date" name="custom_date" class="custom_date">
                            </label>

                            <div class="selected_date">
                                <h3>Selected Date</h3>
                                <p>Thursday, August 21, 2025</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col right_col">
                    <div class="col_heading">

                        <div class="col_1">
                            <h1><span class="main_title">Thursday</span> Time Slots</h1>
                            <div class="sub_title">Manage available time slots for <span>Thursday</span></div>
                        </div>

                        <div class="col_2">
                            <button class="btn mark_off_day">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x w-4 h-4 mr-2"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m14 14-4 4"></path><path d="m10 14 4 4"></path></svg>
                                <span>Mark as Off Day</span>
                            </button>

                            <button class="btn add_time_slot">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4 mr-2"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                                <span>Add Time Slot</span>
                            </button>
                        </div>

                    </div>

                    <div class="time_solts">
                        <!-- Slot will show in here, slot template will copy in here -->
                    </div>

                    <div class="no_slots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-16 h-16 text-gray-300 mb-4"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <h3>No time slots for Thursday</h3>
                        <p>Get started by adding your first time slot for this day</p>
                        <button class="btn add_time_slot">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4 mr-2"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                            <span>Add First Time Slot</span>
                        </button>
                    </div>

                    <div class="no_date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days w-16 h-16 text-gray-300 mx-auto mb-4"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>
                        <h3>Select a custom date to get started</h3>
                        <p>Choose a specific date from the sidebar to manage its time slots</p>
                    </div>

                    <div class="off_day_notice">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x h-4 w-4 text-red-600"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m14 14-4 4"></path><path d="m10 14 4 4"></path></svg>
                        <h3><span class="off_day_sl">Thursday</span> is marked as off day</h3>
                        <p class="off_day_reason">Get started by adding your first time slot for this day</p>
                        <button class="btn remove_off_day">
                            <span>Remove Off Day</span>
                        </button>
                    </div>
                </div>

            </div>
            <div class="picker_modal">
                <div class="container">
                    <div class="close_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                    </div>
                    <h2 class="modal_title">
                        <span class="add_slot_title">Add Time Slot for <span>Wednesday</span></span>
                        <span class="edit_slot_title">Edit Time Slot</span>
                        <span class="off_day_title">Mark <span>Wednesday</span> as Off Day</span>
                    </h2>
                    <div class="time_pickers">
                        <label class="start_time">
                            <span>Start Time</span>
                            <input type="time" name="start_time" class="start_time_input">
                        </label>
                        <label class="end_time">
                            <span>End Time</span>
                            <input type="time" name="end_time" class="end_time_input">
                        </label>
                    </div>
                    <label class="off_day_reason">
                        <span>Reason for Off Day</span>
                        <textarea name="off_day_reason" class="off_day_reason_input" placeholder="Enter reason for marking this day as off day"></textarea>
                    </label>
                    <div class="error_message" show_status="false">Error Message Will Show in here</div>
                    <div class="buttons_grp">
                        <button class="cancel_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-2"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>    
                            Cancel
                        </button>

                        <button class="update_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save w-4 h-4 mr-2"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path><path d="M7 3v4a1 1 0 0 0 1 1h7"></path></svg>
                            Update
                        </button>

                        <button class="save_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save w-4 h-4 mr-2"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path><path d="M7 3v4a1 1 0 0 0 1 1h7"></path></svg>
                            Save
                        </button>

                        <button class="off_day_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x w-4 h-4 mr-2"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m14 14-4 4"></path><path d="m10 14 4 4"></path></svg>
                            Mark as Off Day
                        </button>
                    </div>
                </div>
            </div>

            <!-- Slot Template -->
            <template id="each_time_slot_template">
                    <div class="each_time_slot">
                        <div class="slot_time">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 text-blue-600"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span class="time">10:00 AM - 11:00 AM</span>
                        </div>

                        <div class="duration_rp">Duration: <span class="duration">1 hour</span></div>

                        <div class="each_slot_btns">
                            <button class="edit_btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen w-4 h-4 mr-1"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"></path></svg>
                                <span>Edit</span>
                            </button>
                            <button class="delete_btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 w-4 h-4"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                            </button>
                        </div>
                    </div>
            </template>  
        </div>
    </div>
    <?php
}
