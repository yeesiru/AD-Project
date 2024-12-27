fetch('../User/fetchOtherUser.php')
            .then(response => response.json())
            .then(users => {
                const userCards = document.getElementById('userCards');
                users.forEach(user => {
                    const card = `
                    <div class="card-list swiper-slide">
                        <div class="card-item">
                            <img src="${user.image || 'default-image.png'}" alt="user image" class="user-image">
                            <h2 class="user-name">${user.name}</h2>
                            <p class="user-profession">${user.role}</p>
                            <button class="message-button" onclick="showUserDetails(${encodeURIComponent(JSON.stringify(user))})">Message</button>
                        </div>
                    </div>`;
                    userCards.innerHTML += card;
                });
            });


const swiper = new Swiper('.swiper', {
    direction: 'horizontal',
    slidesPerView: 1,
    loop: true,
    grabCursor: true,
    spaceBetweem: 20,

  
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
      dynamicBullets: true,
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    breakpoints:{
        0:{
            slidesPerView: 1
        },
        768:{
            slidesPerView: 2,
            spaceBetween: 30,
        },
        1024:{
            slidesPerView: 3,
            spaceBetween: 40,
        }
    }

  });