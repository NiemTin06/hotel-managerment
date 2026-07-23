import { API } from "../../api/api.js";

export async function bindUpdateButton(buttonUpdate, form) {
    console.log(form);
    console.log(buttonUpdate);
    form.reset();
    const userId = buttonUpdate.dataset.id;
    const user = await API.get(`admin/users/${userId}`);
    form.dataset.id = userId;
    
    form.elements["username"].value = user.USER_USERNAME || "";
    form.elements["email"].value = user.USER_EMAIL || "";
    form.elements["phone"].value = user.USER_PHONE || "";
    form.elements["role"].value = user.USER_ROLE || "Staff";
    form.elements["status"].value = user.USER_STATUS || "Active";

    // Ẩn ô nhập mật khẩu khi đang ở chế độ Sửa
    const divPassword = document.querySelector("#div-password-input");
    if (divPassword) {
        divPassword.style.display = "none";
    }
}
