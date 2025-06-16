function toggleDarkMode() {
    document.body.classList.toggle("dark");
  }
  
  function openForm(product, price) {
    document.getElementById("buyModal").style.display = "flex";
    document.getElementById("productTitle").innerText = `Buy ${product} (${price})`;
    document.getElementById("productName").value = product;
  }
  
  function closeForm() {
    document.getElementById("buyModal").style.display = "none";
  }
  
  function animateLogo() {
    const logo = document.getElementById("logo");
    logo.style.transition = "transform 1s ease-in-out";
    logo.style.transform = "scale(1.1)";
    setTimeout(() => logo.style.transform = "scale(1)", 1000);
  }
  