export class API {
    static get(url) {
        return this.request(url);
    }

    static post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: data
        });
    }

    static async request(url, options = {}) {
        options.headers = {
            ...options.headers,
            'X-Requested-With': 'XMLHttpRequest'
        };

        if (options.body && !(options.body instanceof FormData)) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(options.body);
        }

        try {
            const response = await fetch(`${APP_URLROOT}/${url}`, options);
            const raw = await response.text();

            let data;

            try {
                data = JSON.parse(raw);
            } catch (error) {
                console.error('Server không trả JSON:', raw);
                throw new Error('Dữ liệu trả về không hợp lệ.');
            }

            if (response.status === 401 && data.redirectUrl) {
                window.location.href = data.redirectUrl;
                return;
            }

            if (!response.ok) {
                throw new Error(data.message || `HTTP Error: ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
}