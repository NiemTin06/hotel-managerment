import { API } from '../../api/api.js';
import {renderRoomType} from '../room-type/render-room-type.js';

// create-item.js
export async function createItem(link, formData) {
    try {
        return await API.post(`${link}/create`, formData);
    } catch (error) {
        console.error(error);
        return { success: false, message: error.message ?? 'Có lỗi xảy ra.' };
    }
}