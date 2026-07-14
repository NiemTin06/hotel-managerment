export function renderPagination(pagination, callback) {

    const container = document.getElementById("pagination");

    container.innerHTML = "";

    if (pagination.totalPage <= 1) {
        return;
    }

    // Previous
    if (pagination.page > 1) {
        container.innerHTML += `
            <button data-page="${pagination.page - 1}">
                <<
            </button>
        `;
    }

    // Các số trang
    for (let i = 1; i <= pagination.totalPage; i++) {

        container.innerHTML += `
            <button
                data-page="${i}"
                class="${i === pagination.page ? "active" : ""}">
                ${i}
            </button>
        `;
    }

    // Next
    if (pagination.page < pagination.totalPage) {
        container.innerHTML += `
            <button data-page="${pagination.page + 1}">
                >>
            </button>
        `;
    }

    container.querySelectorAll("button").forEach(button => {

        button.onclick = () => {

            callback(Number(button.dataset.page));

        };

    });

}