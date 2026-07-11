import { API } from '../../api/api.js';
import {loadRooms} from './load-room.js';
export function changeMulti() {
    const formChangeMulti = document.querySelector("[form-change-multi]");
    formChangeMulti.addEventListener("submit", async (e)=>{
        e.preventDefault();
        const inputChecked = document.querySelectorAll("input[name='id']:checked");
        if (inputChecked.length === 0){
            alert("Vui lòng chọn ít nhất một phòng để thay đổi trạng thái.");
            return;
        }
        else {
            const statusChange = e.target.elements.status.value;
            if (statusChange === ""){
                alert("Vui lòng chọn trạng thái áp dụng");
                return;
            }
            else {
                const ids = Array.from(inputChecked).map(input => input.value);
                console.log(ids)
                let action = formChangeMulti.getAttribute("action");
                try {
                    const data = await API.patch('admin/rooms/change-multi', { ids: ids.join(","), status: statusChange });
                    if(data.success){
                        console.log("Cập nhật trạng thái thành công");
                        loadRooms();
                    }
                    else {
                        console.log(data.message ?? "Cập nhật trạng thái thất bại.");
                        console.warn(data);
                    }
                } catch (error) {
                    console.error(error);
                    console.log("Có lỗi xảy ra khi cập nhật trạng thái.");
                }
            }
        }
    })
}