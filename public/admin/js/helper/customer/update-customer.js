import { API } from "../../api/api.js";

export async function bindUpdateButton(buttonUpdate, form) {
    form.reset();
    const customerId = buttonUpdate.dataset.id;
    const customer = await API.get(`admin/customers/${customerId}`);
    if (!customer) return;

    form.dataset.id = customerId;
    form.elements["customer-fullname"].value = customer.CUSTOMER_FULLNAME || "";
    form.elements["customer-phone"].value = customer.CUSTOMER_PHONE || "";
    form.elements["customer-email"].value = customer.CUSTOMER_EMAIL || "";
    form.elements["customer-cccd"].value = customer.CUSTOMER_CCCD || "";
}