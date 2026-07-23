import { API } from "../api/api.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    if (!form) return;
    console.log(form)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        try {
            const data = await API.post('admin/loginPost', formData);
            if (data.status === 'success') {
                window.location.href = data.redirectUrl;
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error(err);
            alert('Không kết nối được server, vui lòng thử lại!');
        }
    });
});