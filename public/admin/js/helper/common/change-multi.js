import { API } from '../../api/api.js';
import { loadItem } from './load-item.js';
export function changeMulti(label, link, container) {
    const formChangeMulti = document.querySelector("[form-change-multi]");
    formChangeMulti.addEventListener("submit", async (e)=>{
        e.preventDefault();
        const inputChecked = document.querySelectorAll("input[name='id']:checked");
        if (inputChecked.length === 0){
            alert("Vui lòng chọn ít nhất một "+ label +" để thay đổi trạng thái.");
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
                let action = formChangeMulti.getAttribute("action");
                try {
                    let data;
                    if (statusChange == "Delete"){
                        const confirmDelete = confirm("Bạn có chắc muốn xóa các loại loại phòng này?");
                        if (!confirmDelete) return;
                        data = await API.delete(`${link}/delete`, { ids: ids});
                    } else {
                        data = await API.patch(`${link}/change-multi`, { ids: ids.join(","), status: statusChange });
                    }
                    if(data.success){
                        console.log("Cập nhật trạng thái thành công");
                        loadItem(link, container);
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