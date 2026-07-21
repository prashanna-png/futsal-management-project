const links = document.querySelectorAll('.nav-link');
links.forEach(link => {
  link.addEventListener("click",function(){
      links.forEach(item => item.classList.remove("active"));
      this.classList.add("active");
  })
});

const questions = document.querySelectorAll('.question');

questions.forEach(question =>{
  question.addEventListener("click", function(){
    const answer = this.querySelector('.answer');

    if(answer.style.display === 'block'){
      answer.style.display = 'none';
    }
    else{
      answer.style.display='block';
    }
  })
})