import { loadItem } from "./load-item.js";
import { getQueryString, syncForm, updateParams} from "./url.js";
export async function initFilter(link, container, callbackRender) {
    const form = document.querySelector("[filter-form]");
    if (!form) return;

    // Lần đầu vào trang
    syncForm(form);
    let param = getQueryString();
    await loadItem(link, container, callbackRender, param);

    // Submit filter
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(form));
        updateParams({
            ...formData,
            page: 1
        });
        let param = getQueryString();
        await loadItem(link, container, callbackRender, param);
    });

    // Back / Forward
    window.addEventListener("popstate", async () => {
        syncForm(form);
        let param = getQueryString();
        await loadItem(link, container, callbackRender, param);
    });
}