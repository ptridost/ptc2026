// File: /js/app.js - FINAL & COMPLETE VERSION

$(document).ready(function () {
  // --- Manual Triggers for ALL Navbar Dropdowns ---
  $("#userDropdown, #navbarDropdown").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(this);
    dropdownInstance.toggle();
  });

  // --- Scroll-to-Top Button Logic ---
  const scrollProgress = document.getElementById("progress");
  if (scrollProgress) {
    scrollProgress.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });

    let calcScrollValue = () => {
      let pos = document.documentElement.scrollTop;
      let calcHeight =
        document.documentElement.scrollHeight -
        document.documentElement.clientHeight;
      let scrollValue = Math.round((pos * 100) / calcHeight);

      scrollProgress.style.display = pos > 100 ? "grid" : "none";
      scrollProgress.style.background = `conic-gradient(#0E2369 ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
    };

    window.onscroll = calcScrollValue;
    window.onload = calcScrollValue;
  }

  // --- Fade-in on Scroll Animation Logic (for .hidden elements) ---
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
      }
    });
  });
  const hiddenElements = document.querySelectorAll(".hidden");
  hiddenElements.forEach((el) => observer.observe(el));

  // --- RESTORED: Lazy-Loading for Gallery Background Images ---
  const lazyBgElements = document.querySelectorAll(".lazy-bg");
  const lazyBgObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const div = entry.target;
        const bgImage = div.getAttribute("data-bgimage");
        if (bgImage) {
          // Remove the single quotes that might be in the attribute
          div.style.backgroundImage = `url(${bgImage.replace(/'/g, "")})`;
        }
        // Stop observing once the image is loaded
        observer.unobserve(div);
      }
    });
  });
  lazyBgElements.forEach((div) => lazyBgObserver.observe(div));
});
