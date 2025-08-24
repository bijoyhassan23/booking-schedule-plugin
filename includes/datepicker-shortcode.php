<?php
function bkah_date_picker_shortcode($atts) {
    ob_start();
    ?>
        <div class="booking_date_picker">
            <div class="col_left">
                <div class="left_title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-5 h-5 text-blue-600"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    <span>Select Dates</span>
                </div>
                <div class="slot_calendar"></div>
            </div>
            <div class="col_right">
                <div class="slot_title">Available Time Slots</div>
                <div class="slot_time">

                    <div class="each_date_slot">
                        <div class="date_slot_title">Wednesday, August 20</div>
                        <div class="date_time_slots">

                            <label class="time_slot">
                                <input type="radio" name="date_slot_1" value="09:00 - 10:00" booked="true">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>

                            <label class="time_slot">
                                <input type="radio" name="date_slot_1" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>

                            <label class="time_slot">
                                <input type="radio" name="date_slot_1" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>


                            <label class="time_slot">
                                <input type="radio" name="date_slot_1" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>
                            
                        </div>
                    </div>

                    <div class="each_date_slot">
                        <div class="date_slot_title">Wednesday, August 20</div>
                        <div class="date_time_slots">

                            <label class="time_slot">
                                <input type="radio" name="date_slot_2" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>

                            <label class="time_slot">
                                <input type="radio" name="date_slot_2" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>

                            <label class="time_slot">
                                <input type="radio" name="date_slot_2" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>


                            <label class="time_slot">
                                <input type="radio" name="date_slot_2" value="09:00 - 10:00">
                                <span class="start_end_time">09:00 - 10:00</span>
                                <div class="slot_status">
                                    <span class="available">Available</span>
                                    <span class="booked">Booked</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-4 h-4 text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                            </label>
                            
                        </div>
                    </div>

                </div>
                <div class="no_date_selected">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-16 h-16 mx-auto mb-4 opacity-50"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    <div class="title">Select dates to see available slots</div>
                    <div class="sub_title">Choose one or more dates from the calendar</div>
                </div>
            </div>
        </div>
    <?php
    return ob_get_clean();
}

add_shortcode( "date_picker", "bkah_date_picker_shortcode" );