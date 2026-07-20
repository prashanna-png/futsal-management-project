const links = document.querySelectorAll('.nav-link');
links.forEach(link => {
  link.addEventListener("click",function(){
      links.forEach(item => item.classList.remove("active"));
      this.classList.add("active");
  })
});