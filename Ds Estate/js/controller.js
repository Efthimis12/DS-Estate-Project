
function toggleForm() {
    const formSection = document.getElementById("insert_new_Listing");
    if (formSection.style.display === "none") {
        formSection.style.display = "block"; // Show the form
    } else {
        formSection.style.display = "none"; // Hide the form
    }
}

