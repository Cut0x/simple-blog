function toggle_text() {
    const span = document.getElementById("span_txt");

    if(span.style.display == "none") {
        span.style.display = "inline";
    } else {
        span.style.display = "none";
    }
};