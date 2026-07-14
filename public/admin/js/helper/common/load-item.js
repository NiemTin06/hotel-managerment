import { API } from '../../api/api.js';
import {renderRoomType} from '../room-type/render-room-type.js';

export async function loadItem (link, container){
    try {   
        const tmp= await API.get(`${link}/data`);
        const records = tmp["record"];
        renderRoomType(records, container)
    } catch (error) {
        console.error('Lỗi khi load dữ liệu phòng:', error);
        const roomListContainer = document.querySelector("." + container);
        if (roomListContainer) {
            roomListContainer.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Không tải được dữ liệu phòng</td></tr>';
        }
    }
}