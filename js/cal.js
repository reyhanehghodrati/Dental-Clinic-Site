
// قیمت‌های پیش‌فرض برای هر خدمت، پزشک و نوع خدمت
const pricing = {
    services: {
        cleaning: 50000, // پروتز دندانی
        filling: 80000,  // دندان پزشکی
        whitening: 100000 // رادیولوژی
    },
    serviceType: {
        basic: 1,    // خدمت پایه (ضریب 1)
        advanced: 1.5, // لمینت
        cosmetic: 2, // ارتودنسی
        surgery: 2.5, // جراحی
        emergency: 3  // اورژانسی
    },
    doctor: {
        "dr-ahmadi": 1.2,  // دکتر احمدی
        "dr-mohammadi": 1.5, // دکتر محمدی
        "dr-karimi": 1.8   // دکتر کریمی
    },
    quantity: {
        1: 1,    // یک دندان
        2: 2,    // دو دندان
        3: 3,    // سه دندان
        4: 4,    // چهار دندان
        jaw: 10, // یک فک کامل
        "both-jaws": 20 // دو فک کامل
    }
};

const discountCodes = {
    "OFF10": 0.9, // 10 درصد تخفیف
    "OFF20": 0.8, // 20 درصد تخفیف
    "VIP": 0.7    // 30 درصد تخفیف
};

const calculateButton = document.getElementById("calculateButton");
const resultSection = document.getElementById("result");

calculateButton.addEventListener("click", () => {
    const service = document.getElementById("services").value;
    const serviceType = document.getElementById("serviceType").value;
    const doctor = document.getElementById("doctor").value;
    const quantity = document.getElementById("quantity").value;
    const discountCode = document.getElementById("discountCode").value.trim();

    let basePrice = pricing.services[service];
    let serviceMultiplier = pricing.serviceType[serviceType];
    let doctorMultiplier = pricing.doctor[doctor];
    let quantityMultiplier = pricing.quantity[quantity];

    let totalPrice = basePrice * serviceMultiplier * doctorMultiplier * quantityMultiplier;

    if (discountCode && discountCodes[discountCode]) {
        totalPrice *= discountCodes[discountCode];
    }

    resultSection.textContent = `هزینه کل: ${totalPrice.toLocaleString('fa-IR')} تومان`;
});
