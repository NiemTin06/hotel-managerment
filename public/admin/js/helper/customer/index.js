import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";
import { initPagination } from "../common/pagination.js";
import { renderCustomer } from "./render-customer.js";

document.addEventListener("DOMContentLoaded", initCustomer);

export async function initCustomer() {
    await loadItem("admin/customers", "customer-list", renderCustomer);
    initFilter("admin/customers", "customer-list", renderCustomer);
    initPagination("admin/customers", "customer-list", renderCustomer);
    changeMulti("Khách hàng", "admin/customers", "customer-list", renderCustomer);
}