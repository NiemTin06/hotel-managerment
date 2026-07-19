import { loadItem } from "./load-item.js";
import { getQueryString, updateParams } from "./url.js";

export function renderPagination(page, totalPage) {
    const pagination = document.querySelector(".pagination");
    let html = "";
    // Previous
    html += `
        <li class="page-item ${page === 1 ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page - 1}">
                &laquo;
            </a>
        </li>
    `;

    // Danh sách trang
    for (let i = 1; i <= totalPage; i++) {
        html += `
            <li class="page-item ${i === page ? "active" : ""}">
                <a class="page-link" href="#" data-page="${i}">
                    ${i}
                </a>
            </li>
        `;
    }

    // Next
    html += `
        <li class="page-item ${page === totalPage ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page + 1}">
                &raquo;
            </a>
        </li>
    `;

    pagination.innerHTML = html;
}

export function initPagination(link, container, callbackRender){
    const pagination = document.querySelector(".pagination");
    pagination.addEventListener("click", async (e) => {
        e.preventDefault();
        const button = e.target.closest("[data-page]");
        if (!button) return ;
        const newPage = Number(button.dataset.page);
        updateParams({
            page: newPage
        });
        let param = getQueryString();
        await loadItem(link, container, callbackRender, param);
    })
}