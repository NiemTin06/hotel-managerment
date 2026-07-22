import { API } from "../../api/api.js";

export async function bindUpdateBookingButton(buttonUpdate, form) {
    form.reset();
    const id = buttonUpdate.dataset.id;
    const res = await API.get(`admin/bookings/${id}`);
    if (!res) return;

    form.dataset.id = id;
    form.elements["booking-customer"].value = res.BOOKING_CUSTOMER_ID || "";
    form.elements["booking-roomtype"].value = res.BOOKING_ROOMTYPE_ID || "";
    form.elements["booking-checkin"].value = res.BOOKING_CHECKIN || "";
    form.elements["booking-checkout"].value = res.BOOKING_CHECKOUT || "";
    form.elements["booking-status"].value = res.BOOKING_STATUS || "Pending";
    form.elements["booking-price"].value = res.BOOKING_TOTAL_PRICE || 0;
    form.elements["booking-note"].value = res.BOOKING_NOTE || "";
}