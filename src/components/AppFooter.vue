<template>
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-social">
          <p>WallaShop</p>
          <div class="social-icons">
            <a href="#"><img src="instagram.png" alt="Instagram"></a>
            <a href="#"><img src="facebook.png" alt="Facebook"></a>
            <a href="#"><img src="twitter.png" alt="Twitter"></a>
            <a href="#"><img src="youtube.png" alt="YouTube"></a>
          </div>
        </div>
        <div class="footer-logo">
          <img src="/logotipo-sinfondo.png" alt="WallaShop" class="logo">
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 Wallashop. All rights reserved.</p>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'AppFooter',
  };
  </script>
  
  <style scoped>
  .footer {
    background-color: #FFF;
    padding: 20px 0;
    box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  .footer-content {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-left: 15%;
  }
  
  .footer-logo img {
    max-width: 150px;
    height: auto;
  }
  
  .footer-social {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
  }
  
  .social-icons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
  }
  
  .social-icons a img {
    width: 24px;
    height: 24px;
    transition: transform 0.3s ease;
  }
  
  .social-icons a img:hover {
    transform: scale(1.1);
  }
  
  .footer-bottom {
    margin-top: 20px;
    border-top: 1px solid #8292A4;
    padding-top: 10px;
    width: 100%;
    text-align: center;
  }
  
  ul {
    list-style: none;
  }
  
  .example-2 {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .example-2 .icon-content {
    margin: 0 10px;
    position: relative;
  }
  
  .example-2 .icon-content .tooltip {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    padding: 6px 10px;
    border-radius: 5px;
    opacity: 0;
    visibility: hidden;
    font-size: 14px;
    transition: all 0.3s ease;
  }
  
  .example-2 .icon-content:hover .tooltip {
    opacity: 1;
    visibility: visible;
    top: -50px;
  }
  
  .example-2 .icon-content a {
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    color: #4d4d4d;
    background-color: #fff;
    transition: all 0.3s ease-in-out;
  }
  
  .example-2 .icon-content a:hover {
    box-shadow: 3px 2px 45px 0px rgb(0 0 0 / 12%);
  }
  
  .example-2 .icon-content a svg {
    position: relative;
    z-index: 1;
    width: 30px;
    height: 30px;
  }
  
  .example-2 .icon-content a:hover {
    color: white;
  }
  
  .example-2 .icon-content a .filled {
    position: absolute;
    top: auto;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 0;
    background-color: #000;
    transition: all 0.3s ease-in-out;
  }
  
  .example-2 .icon-content a:hover .filled {
    height: 100%;
  }
  
  .example-2 .icon-content a[data-social="instagram"] .filled,
  .example-2 .icon-content a[data-social="instagram"] ~ .tooltip {
    background-color: #E1306C;
  }
  
  .example-2 .icon-content a[data-social="facebook"] .filled,
  .example-2 .icon-content a[data-social="facebook"] ~ .tooltip {
    background-color: #3b5998;
  }
  
  .example-2 .icon-content a[data-social="twitter"] .filled,
  .example-2 .icon-content a[data-social="twitter"] ~ .tooltip {
    background-color: #1DA1F2;
  }
  
  .example-2 .icon-content a[data-social="youtube"] .filled,
  .example-2 .icon-content a[data-social="youtube"] ~ .tooltip {
    background-color: #FF0000;
  }
  
  #usrLogedPfp {
    display: none;
    justify-content: space-around;
    align-items: center;
    border-radius: 10px;
    padding: 5px;
    background-color: #0E2945;
    color: white;
    cursor: pointer;
    margin: 0 2em 0 -4em;
    width: 10em;
    transition: transform 0.3s ease;
  }
  
  #usrLogedPfp:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  }
  
  #usrLogedPfp:active {
    transform: translateY(0);
  }
  
  #usrLogedPfp img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
  }
  </style>
  