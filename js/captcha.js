"use strict";

const inputDisableEl = document.querySelector(".disable");
const inputEnableEl = document.querySelector(".captcha");
const btnRefereshEl = document.querySelector(".btn");
const messagEl = document.querySelector(".message");
const btnSubmit = document.querySelector(".btn-submit");

let captchaTxt = null;

//==================Generate Random Characters=================
function generateCaptcha() {
    const randomString = Math.random().toString(36).substring(2, 8);
    const randomStringArr = randomString.split("");
    const changeString = randomStringArr.map((char) =>
        Math.random() > 0.5 ? char.toUpperCase() : char,
    );
    captchaTxt = changeString.join("  ");
    inputDisableEl.value = captchaTxt;
}

//==================Generate Random Characters=================
function submitBtn() {
    captchaTxt = captchaTxt
        .split(" ")
        .filter((char) => char != "")
        .join(" ");
    console.log(captchaTxt);
    messagEl.classList.add("active");

    if (inputEnableEl.value === captchaTxt) {
        messagEl.innerHTML = "You've Entered Correct Captcha.......";
        messagEl.style.color = "#28b463";
    } else {
        messagEl.innerHTML = "You've Entered Invalid Captcha.......";
        messagEl.style.color = "#C70039";
    }
}

//==================Refresh Btn =================
const refereshBtn = () => {
    generateCaptcha();
    submitBtn();
    inputEnableEl.value = "";
    messagEl.classList.remove("active");
};

btnRefereshEl.addEventListener("click", refereshBtn);
btnSubmit.addEventListener("click", submitBtn);
generateCaptcha();