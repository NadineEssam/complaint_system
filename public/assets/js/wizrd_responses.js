document.addEventListener('DOMContentLoaded', function () {

    let currentStep = 0;
    let steps = document.querySelectorAll('.step');
    let navLinks = document.querySelectorAll('#wizard .nav-link');

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle('d-none', i !== index);
            navLinks[i].classList.toggle('active', i === index);
        });

        // 👇 تعطيل الأزرار حسب الحالة
        document.getElementById('prevBtn').disabled = index === 0;
        document.getElementById('nextBtn').disabled = index === steps.length - 1;
    }

    
    showStep(currentStep);

   
    document.getElementById('nextBtn').addEventListener('click', function () {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    
    document.getElementById('prevBtn').addEventListener('click', function () {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    navLinks.forEach((link, index) => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            currentStep = index;
            showStep(currentStep);
        });
    });

});