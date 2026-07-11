import { API } from '../../api/api.js';
import {renderRoomType} from '../room-type/render-room-type.js';

export async function loadItem (link, container){
    try {
        
        const records = await API.get(`${link}/data`);
        renderRoomType(records, container)
        // console.log("ok")
        console.log(records)
    } catch (error) {
        console.error('Lỗi khi load dữ liệu phòng:', error);
        const roomListContainer = document.querySelector("." + container);
        if (roomListContainer) {
            roomListContainer.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Không tải được dữ liệu phòng</td></tr>';
        }
    }
}