import { API } from '../../api/api.js';
import {renderRooms} from './render-room.js';

export async function loadRooms (){
    try {
        const rooms = await API.get('rooms/data');
        renderRooms(rooms);
    } catch (error) {
        console.error('Lỗi khi load dữ liệu phòng:', error);
        const roomListContainer = document.querySelector('#room-list');
        if (roomListContainer) {
            roomListContainer.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Không tải được dữ liệu phòng</td></tr>';
        }
    }
}