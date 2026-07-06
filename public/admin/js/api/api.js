
// Ham xy ly api call
export class API {
    static get(url){
        return this.request(url);
    }
    static post(url, data){
        return this.request(url, {
            method: 'POST',
            headers: { 
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
    }

    static patch(url,data){
        return this.request(url, {
            method: 'PATCH',
            headers: { 
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
    }

    static delete(url){
        return this.request(url, {
            method: 'DELETE',
        });
    }

    static async request(url, options = {}) {
        try {
            const response = await fetch(`${APP_URLROOT}/${url}`, options);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error in API request:', error);
            throw error;
        }
    }
}