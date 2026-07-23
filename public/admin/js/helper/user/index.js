import { changeMulti } from "../common/change-multi.js";
import { initFilter } from "../common/filter.js";
import { loadItem } from "../common/load-item.js";
import { initPagination } from "../common/pagination.js";
import { renderUser } from "./render-user.js";

export async function initUser() {
    await loadItem("admin/users", "user-list", renderUser);
    initFilter("admin/users", "user-list", renderUser);
    initPagination("admin/users", "user-list", renderUser);
    changeMulti("Trạng thái", "admin/users", "user-list", renderUser);
}