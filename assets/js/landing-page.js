
const navLinks = document.querySelectorAll('.nav-link');

navLinks.forEach(link => {
  link.addEventListener("click", function () {
    navLinks.forEach(item => item.classList.remove("active"));
    this.classList.add("active");
  });
});

window.addEventListener('scroll', () => {
  const sections  = document.querySelectorAll('section[id]');
  const scrollPos = window.scrollY + 120;

  sections.forEach(section => {
    const top    = section.offsetTop;
    const height = section.offsetHeight;
    const id     = section.getAttribute('id');
    const link   = document.querySelector(`.nav-link[href="#${id}"]`);

    if (link && scrollPos >= top && scrollPos < top + height) {
      navLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
    }
  });
});

window.addEventListener('scroll', () => {
  const nav = document.querySelector('.nav-bar');
  if (window.scrollY > 20) {
    nav.style.boxShadow = '0 30px 40px rgba(0,0,0,0.08)';
  } else {
    nav.style.boxShadow = 'none';
  }
});

const questions = document.querySelectorAll('.question');

questions.forEach(question => {
  question.addEventListener("click", function () {
    const answer      = this.querySelector('.answer');
    const isOpen      = answer.classList.contains('open');

    questions.forEach(q => {
      q.querySelector('.answer').classList.remove('open');
      q.classList.remove('active');
    });

    if (!isOpen) {
      answer.classList.add('open');
      this.classList.add('active');
    }
  });
});

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity   = '1';
      entry.target.style.transform = 'translateY(0)';
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.card, .step-card, .benefit-card').forEach((el, i) => {
  el.style.opacity    = '0';
  el.style.transform  = 'translateY(30px)';
  el.style.transition = `opacity 0.5s ease ${i * 0.08}s, transform 0.5s ease ${i * 0.08}s`;
  revealObserver.observe(el);
});