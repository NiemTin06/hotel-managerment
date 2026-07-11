// pop up create item 
const btnCreatePopup = document.querySelector("#btnCreatePopup");
const btnClosePopup = document.querySelector("#btnClosePopup")
const popupCreate = document.querySelector(".create-popup-container")
const popup = document.getElementById("createPopup");

if (btnClosePopup){
    btnClosePopup.addEventListener("click", () => {
        popupCreate.classList.remove("show");
    });
}
if (btnCreatePopup){
    btnCreatePopup.addEventListener("click", () => {
        popupCreate.classList.add("show");
    });
}

if (popup){
    popup.addEventListener("click", (e)=>{
        if (e.target === popup) {
            popupCreate.classList.remove("show");
        }
    })
}


