import { API } from '../../api/api.js';
import {renderRoomType} from '../room-type/render-room-type.js';
import { renderPagination } from './pagination.js';

export async function loadItem (link, container, callbackRender, param = null){
    try {  
        console.log(`${link}/data${param ? param : ""}`)
        const tmp= await API.get(`${link}/data${param ? param : ""}`);
        const records = tmp["record"];
        const pagination = tmp['pagination'];
        const roomtypes= tmp["record-rooms-type"];
        callbackRender(records, container, roomtypes);
        renderPagination(pagination.page, pagination.totalPage);
    } catch (error) {
        console.error('Lỗi khi load dữ liệu phòng:', error);
        const roomListContainer = document.querySelector("." + container);
        if (roomListContainer) {
            roomListContainer.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Không tải được dữ liệu phòng</td></tr>';
        }
    }
}