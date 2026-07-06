import {changeMulti} from '../helper/change-multi.js';
import {loadRooms} from '../helper/load-room.js';


async function init() {
    await loadRooms();
    changeMulti();
}

document.addEventListener("DOMContentLoaded", init);