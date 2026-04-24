import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {

    const items = document.querySelectorAll(".faq-item");

    items.forEach((item) => {
        const button = item.querySelector(".faq-question");
        const answer = item.querySelector(".faq-answer");
        const icon = button.querySelector("i");

        button.addEventListener("click", () => {

            // بستن همه آیتم‌ها
            items.forEach((otherItem) => {
                if (otherItem !== item) {
                    otherItem.querySelector(".faq-answer").classList.add("hidden");

                    const otherIcon = otherItem.querySelector(".faq-question i");
                    otherIcon.classList.remove("fa-chevron-up");
                    otherIcon.classList.add("fa-chevron-down");
                }
            });

            // toggle این یکی
            answer.classList.toggle("hidden");

            // تغییر آیکون
            if (answer.classList.contains("hidden")) {
                icon.classList.remove("fa-chevron-up");
                icon.classList.add("fa-chevron-down");
            } else {
                icon.classList.remove("fa-chevron-down");
                icon.classList.add("fa-chevron-up");
            }

        });

    });

});