let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active')
   navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   navbar.classList.remove('active');
}

subImages = document.querySelectorAll('.update-product .image-container .sub-images img');
mainImage = document.querySelector('.update-product .image-container .main-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      let src = images.getAttribute('src');
      mainImage.src = src;
   }


   function toggleDetail(orderId) {
   const detailRow = document.getElementById('detail-row-' + orderId);
   // Toggle the visibility of the detail row
   if (detailRow.style.display === "table-row") {
      detailRow.style.display = "none";
   } else {
      detailRow.style.display = "table-row";
   }
}


   function closePopup() {
      document.getElementById('orderDetailPopup').style.display = 'none';
   }

});
