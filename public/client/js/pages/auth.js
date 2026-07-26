import { API } from '../api/api.js';

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    handleSubmit(loginForm, 'login');
    handleSubmit(registerForm, 'register');
});

function handleSubmit(form, url) {
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            const formData = new FormData(form);
            const data = await API.post(url, formData);

            if (data.status === 'success') {
                window.location.href = data.redirectUrl;
                return;
            }

            alert(data.message);
        } catch (error) {
            console.error(error);
            alert('Không kết nối được server, vui lòng thử lại!');
        }
    });
}