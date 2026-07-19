// Hàm láy param 
export function getParams() {
    return new URLSearchParams(window.location.search);
}

//Hàm ghi đè url 
export function pushParams(params) {
    const query = params.toString();
    const url = query
    ? `${window.location.pathname}?${query}`
    : window.location.pathname;
    
    history.pushState({}, "", url);
}

//Hàm láy chuổi query
export function getQueryString() {
    const params = getParams();
    return params.toString() ? `?${params.toString()}` : "";
}
//Hàm cập nhật đoạn param 
export function updateParams(data) {
    const params = getParams();
    Object.entries(data).forEach(([key, value]) => {
        if (value === "" || value == null) {
            params.delete(key);
        } else {
            params.set(key, value);
        }
    });
    pushParams(params);
}

//Hàm động bộ URL và FORM
export function syncForm(form) {
    const params = getParams();
    Array.from(form.elements).forEach(element => {
        if (!element.name) return;
        element.value = params.get(element.name) ?? "";
    });
}
