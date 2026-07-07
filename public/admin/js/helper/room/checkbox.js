export function checkboxMulti() {
    const checkBoxMulti = document.querySelector("[checkbox-multi]")
    if (checkBoxMulti) {
        const inputId = document.querySelectorAll("input[name='id']");
        if (inputId){
        // checkBoxMulti.addEventListener("click", () => {
        //     inputId.forEach(input => {
        //         input.checked = checkBoxMulti.checked;
        //     });
        // });
        if (!checkBoxMulti.dataset.bound) {
        checkBoxMulti.addEventListener("click", () => {
            // Query lại tại thời điểm click để lấy đúng checkbox con hiện tại (mới nhất)
            document.querySelectorAll("input[name='id']").forEach(input => {
                input.checked = checkBoxMulti.checked;
            });
        });
        checkBoxMulti.dataset.bound = "true";
    }
        inputId.forEach(input => {
            input.addEventListener("click", () => {
                const countChecked = document.querySelectorAll("input[name='id']:checked").length;
                if (countChecked == inputId.length) {
                    checkBoxMulti.checked = true;
                } else {
                    checkBoxMulti.checked = false;
                }
            });
        });
        }
    }
}
