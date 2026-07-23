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
        if (
            options.body
            && !(options.body instanceof FormData)
        ) {
            options.headers = {
                ...options.headers,
                'Content-Type': 'application/json'
            };

            options.body = JSON.stringify(options.body);
        }

        const response = await fetch(
            `${APP_URLROOT}/${url}`,
            options
        );

        const text = await response.text();

        try {
            return JSON.parse(text);
        } catch (error) {
            console.error(text);
            throw new Error('Dữ liệu trả về không hợp lệ.');
        }
    }
}
