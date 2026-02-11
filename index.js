document.addEventListener("DOMContentLoaded", function () {

    const message = document.getElementById("message");
    const summaryBox = document.getElementById("summaryBox");
    const previewImage = document.getElementById("previewImage");
    const profilePicInput = document.getElementById("profilePic");

    let summaryNode = null;

    // Profile Picture Preview
    profilePicInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = "none";
        }
    });

    // Register Button
    document.getElementById("submitBtn").addEventListener("click", function () {

        let username = document.getElementById("username").value.trim();
        let email = document.getElementById("email").value.trim();
        let phone = document.getElementById("phone").value.trim();
        let password = document.getElementById("password").value.trim();
        let confirmPassword = document.getElementById("confirmPassword").value.trim();
        let profileFile = profilePicInput.files[0];

        let emailPattern = /^[A-Za-z]{2,}@[A-Za-z]{3,4}\.[A-Za-z]{2,3}$/;
        let phonePattern = /^[0-9]{10}$/;
        let passwordPattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[&,$,#@]).{7,}$/;

        if (!username || !email || !phone || !password || !confirmPassword) {
            message.innerHTML = "All fields are mandatory!";
            message.style.color = "red";
            return;
        }

        if (!emailPattern.test(email)) {
            message.innerHTML = "Invalid Email!";
            message.style.color = "red";
            return;
        }

        if (!phonePattern.test(phone)) {
            message.innerHTML = "Phone must be 10 digits!";
            message.style.color = "red";
            return;
        }

        if (!passwordPattern.test(password)) {
            message.innerHTML = "Weak Password!";
            message.style.color = "red";
            return;
        }

        if (password !== confirmPassword) {
            message.innerHTML = "Passwords do not match!";
            message.style.color = "red";
            return;
        }

        if (!profileFile) {
            message.innerHTML = "Please upload a profile picture!";
            message.style.color = "red";
            return;
        }

        message.innerHTML = "Registration Successful!";
        message.style.color = "green";

        // Create summary
        summaryBox.innerHTML = ""; // clear old summary
        summaryNode = document.createTextNode(
            `Username: ${username} | Email: ${email} | Phone: ${phone}`
        );
        summaryBox.appendChild(summaryNode);
        summaryBox.style.display = "block";
    });

    // Remove Summary
    document.getElementById("removeSummaryBtn").addEventListener("click", function () {
        if (summaryNode) {
            summaryBox.removeChild(summaryNode);
            summaryBox.style.display = "none";
            message.innerHTML = "Summary removed.";
            message.style.color = "orange";
        }
    });

});
