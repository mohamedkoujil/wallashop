<template>
  <AppHeader @search="handleSearch"></AppHeader>
  <div class="product-detail">
    <h1>Detalle del Producto</h1>
    <div class="container" v-if="product">
      <div class="product-content">
        <div class="img-button">
          <img :src="product.images" alt="Product image" class="product-image">
          <button class="contactButton" @click="buyProduct">
            Solicitar Compra
            <div class="iconButton">
              <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0h24v24H0z" fill="none"></path>
                <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                  fill="currentColor"></path>
              </svg>
            </div>
          </button>
        </div>
        <div class="product-info">
          <div class="flex">
            <div v-if="this.owner" class="ownerInfo">
              <img :src="owner.profilepicture" alt="Owner profile picture">
              <p>{{ owner.personname }}</p>
              <p>{{ owner.email }}</p>
              <p>{{ owner.rate }} ★</p>
            </div>
          </div>
          <h2>{{ product.productname }}</h2>
          <p class="product-category"><strong>Categoría:</strong> {{ product.category }}</p>
          <p class="product-description"><strong>Descripción:</strong> {{ product.description }}</p>
          <p class="product-price"><strong>Precio:</strong> {{ product.price }} €</p>
          <p class="product-location"><strong>Ubicación:</strong> {{ product.location }}</p>

          <div class="buyProcess" v-if="buyProcess">
            <p v-if="user.id == product.ownerid">No puedes comprar tu propio producto</p>
            <div v-else>
              <p>¿Estás seguro de que deseas comprar este producto?</p>
              <button @click="confirmBuyProduct">Sí</button>
              <button @click="buyProcess = false">No</button>
            </div>
          </div>

          <div class="heart-container" title="Like" @click="toggleFavorite">
            <input type="checkbox" class="checkbox" id="Give-It-An-Id" v-model="isFavorite">
            <div class="svg-container">
              <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z">
                </path>
              </svg>
              <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z">
                </path>
              </svg>
              <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                <polygon points="10,10 20,20"></polygon>
                <polygon points="10,50 20,50"></polygon>
                <polygon points="20,80 30,70"></polygon>
                <polygon points="90,10 80,20"></polygon>
                <polygon points="90,50 80,50"></polygon>
                <polygon points="80,80 70,70"></polygon>
              </svg>
            </div>
          </div>
        </div>
      </div>
      <div class="valorations">
        <h2>Valoraciones del vendedor</h2>
        <div v-if="infoValorations" class="valorations-comments">
          <div class="combinedData" v-for="(combinedData, index) in infoValorations" :key="index">
            <img :src="combinedData.profilepicture" alt="Profile picture">
            <div class="flex column ">
              <div class="flex flex-start border-bottom-light">
                <p>{{ combinedData.rate }} ★</p>
                <p>{{ combinedData.email }}</p>
                <p>{{ combinedData.personname }}</p>
              </div>
              <div class="flex flex-start">
                <p>{{ combinedData.comment }}</p>
                </div>
            </div>
          </div>
        </div>
        <p v-else>No hay valoraciones</p>
      </div>
    </div>
    <div v-else id="loading">
      <div class="loader">
        <div class="justify-content-center jimu-primary-loading"></div>
      </div>
    </div>
  </div>
  <AppFooter />
</template>

<script>
import AppHeader from '../components/AppHeader.vue';
import AppFooter from '../components/AppFooter.vue';

export default {
  props: ['id'],
  data() {
    return {
      product: null,
      isFavorite: false,
      searchTerm: '',
      buyProcess: false,
      user: JSON.parse(localStorage.getItem('user')) || null,
      owner: null,
      valoraciones: null,
      infoValorations: null
    };
  },
  created() {
    this.fetchProduct();
    this.checkIfFavorite();
  },
  components: {
    AppHeader,
    AppFooter
  },
  methods: {
    fetchProduct() {
      fetch(`http://44.218.60.222:8080/index.php?path=product&id=${this.id}`)
        .then(response => response.json())
        .then(data => {
          this.product = data;
          console.log('Product:', this.product);
          this.fetchOwner();
          this.ownerValorations();
        })
        .catch(error => {
          console.error('Error fetching product:', error);
        });
    },
    async checkIfFavorite() {
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          this.isFavorite = false;
          return;
        }
        const response = await fetch(`http://44.218.60.222:8080/index.php?path=get-favorites&userid=${user.id}`);
        const data = await response.json();
        this.isFavorite = data.some(favorite => favorite.id === this.id);
      } catch (error) {
        console.error('Error checking favorite:', error);
      }
    },
    async toggleFavorite() {
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          alert('You need to log in to add favorites');
          //Dejar el corazon como estaba
          document.getElementById('Give-It-An-Id').checked = false;
        }
        let response = await fetch('http://44.218.60.222:8080/index.php?path=toggle-favorite', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            userid: user.id,
            productid: this.id
          })
        });
        const data = await response.json();
        console.log('Favorite response:', data);
        if (data.status === 'Favorite added') {
          this.isFavorite = true;
        } else if (data.status === 'Favorite removed') {
          this.isFavorite = false;
        }
      } catch (error) {
        console.error('Error toggling favorite:', error);
      }
    },
    async buyProduct() {
      // Check if the user is logged in
      if (!this.user) {
        alert('You need to be logged in to buy products');
        return;
      }
      if (!this.buyProcess) {
        this.buyProcess = true;
      } else {
        console.log('Buying product:', this.product);
        try {
          const user = JSON.parse(localStorage.getItem('user'));
          if (!user) {
            alert('Tienes que estar logueado para comprar productos');
            return;
          }

          if (user.balance < this.product.price) {
            console.log('Balance insuficiente:' + user.balance + ' < ' + this.product.price);
            alert('Balance insuficiente');
            return;
          }

          const response = await fetch('http://44.218.60.222:8080/index.php?path=request-purchase', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(
              {
                userid: user.id,
                ownerid: this.product.ownerid,
                productid: this.product.id,
              })
          });

          const data = await response.json();
          if (data.status === 'Purchase request sent') {
            alert('Solicitud de compra creada');
            this.$router.push('/');
          } else if (data.status == 'Purchase request already sent') {
            alert('Solicitud de compra ya enviada');
          }
          else {
            alert('Error durante la compra');
          }
        } catch (error) {
          console.error('Error comprando:', error);
        }
      }

    },
    handleSearch(term) {
      this.searchTerm = term;
    },
    fetchOwner() {
      fetch(`http://44.218.60.222:8080/index.php?path=user&id=${this.product.ownerid}`)
        .then(response => response.json())
        .then(data => {
          this.owner = data;
          console.log('Owner:', this.owner);
        })
        .catch(error => {
          console.error('Error fetching owner:', error);
        });
    },
    async confirmBuyProduct() {
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          alert('Tienes que estar logueado para comprar productos');
          return;
        }

        if (user.balance < this.product.price) {
          alert('Balance insuficiente');
          return;
        }

        const response = await fetch('http://44.218.60.222:8080/index.php?path=request-purchase', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(
            {
              userid: user.id,
              ownerid: this.product.ownerid,
              productid: this.product.id,
            })
        });

        const data = await response.json();
        if (data.status === 'Purchase request sent') {
          alert('Compra realizada con éxito');
          this.$router.push('/');
        } else if (data.status == 'Purchase request already sent') {
          alert('Solicitud de compra ya enviada');
        }
        else {
          alert('Error durante la compra');
        }
      } catch (error) {
        console.error('Error buying product:', error);
      }
    },
    async ownerValorations() {
      try {
        const response = await fetch('http://44.218.60.222:8080/index.php?path=user-valorations&userid=' + this.product.ownerid);
        const valoracionesData = await response.json();
        console.log('Valoraciones:', valoracionesData);

        const infoValorations = [];
        for (const valoration of valoracionesData) {
          const response = await fetch('http://44.218.60.222:8080/index.php?path=get-info-valorations&userid=' + valoration.userid);
          const userData = await response.json();
          console.log('Info valorations:', userData);

          const combinedData = {
            comment: valoration.comment,
            rate: valoration.rate,
            email: userData.email,
            personname: userData.personname,
            profilepicture: userData.profilepicture
          };
          console.log('Combined data:', combinedData);
          infoValorations.push(combinedData);
        }

        this.infoValorations = infoValorations;
      } catch (error) {
        console.error('Error fetching info valorations:', error);
      }
    },

    async fetchEmailValorations() {
      try {
        const infoValorations = [];
        for (const valoration of this.valoraciones) {
          const response = await fetch('http://44.218.60.222:8080/index.php?path=get-info-valorations&userid=' + valoration.userid);
          const data = await response.json();
          console.log('Info valorations:', data);
          infoValorations.push(data);
        }
        this.infoValorations = infoValorations;
      } catch (error) {
        console.error('Error fetching info valorations:', error);
      }
    }

  }
};
</script>

<style scoped>
/* General Styles */
body {
  font-family: 'Merriweather', serif;
  background-color: #F4F4F4;
  color: #0E2945;
}

/* Container */
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: auto;
}

/* Product Detail */
.product-detail {
  padding: 40px;
  text-align: center;
}

h1 {
  font-weight: 700;
  color: #0E2945;
  margin-bottom: 20px;
}

h2 {
  font-weight: 700;
  color: #0E2945;
  margin: 10px 0;
}

.product-content {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  gap: 20px;
  width: 75%;
  margin: 40px auto;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: #0E2945 0 4px 8px;
  padding: 20px;
  position: relative;
}

.img-button {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.product-image {
  width: 100%;
  max-width: 400px;
  border-radius: 10px;
  border: 2px solid #0E2945;
}

.product-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
  gap: 10px;
}

.flex {
  display: flex;
  width: 100%;
  justify-content: space-between;
}

.ownerInfo {
  display: flex;
  background-color: #0E2945;
  color: white;
  padding: 10px;
  border-radius: 10px;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  transition: 0.3s;
}

.ownerInfo:hover {
  cursor: pointer;
  transform: translateY(-2px);
}

.ownerInfo img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.product-category,
.product-description,
.product-price,
.product-location {
  font-weight: 400;
  font-size: 16px;
  margin: 5px 0;
}

.product-category strong,
.product-description strong,
.product-price strong,
.product-location strong {
  color: #0E2945;
}

/* Buy Process */
.buyProcess {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
  background-color: #0E2945;
  color: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: #0E2945 0 4px 8px;
  z-index: 100;
}

.buyProcess button {
  background-color: #fff;
  color: #0E2945;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 700;
  transition: 0.3s;
  margin: 1em;
}

.buyProcess button:hover {
  transform: translateY(-2px);
}

.buyProcess button:active {
  transform: translateY(2px);
}

/* Heart Container */
.heart-container {
  --heart-color: rgb(240, 8, 0);
  position: absolute;
  right: 10px;
  top: 10px;
  width: 30px;
  height: 30px;
  transition: .3s;
}

.heart-container .checkbox {
  position: absolute;
  left: -5px;
  top: -5px;
  width: 110%;
  height: 110%;
  opacity: 0;
  z-index: 20;
  cursor: pointer;
  padding: 10em;
}

.heart-container .svg-container {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.heart-container .svg-outline,
.heart-container .svg-filled {
  fill: var(--heart-color);
  position: absolute;
}

.heart-container .svg-filled {
  animation: keyframes-svg-filled 1s;
  display: none;
}

.heart-container .svg-celebrate {
  position: absolute;
  animation: keyframes-svg-celebrate .5s;
  animation-fill-mode: forwards;
  display: none;
  stroke: var(--heart-color);
  fill: var(--heart-color);
  stroke-width: 2px;
}

.heart-container .checkbox:checked~.svg-container .svg-filled {
  display: block;
}

.heart-container .checkbox:checked~.svg-container .svg-celebrate {
  display: block;
}

@keyframes keyframes-svg-filled {
  0% {
    transform: scale(0);
  }

  25% {
    transform: scale(1.2);
  }

  50% {
    transform: scale(1);
    filter: brightness(1.5);
  }
}

@keyframes keyframes-svg-celebrate {
  0% {
    transform: scale(0);
  }

  50% {
    opacity: 1;
    filter: brightness(1.5);
  }

  100% {
    transform: scale(1.4);
    opacity: 0;
    display: none;
  }
}

/* Contact Button */
.contactButton {
  background: #0E2945;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.3s;
  margin: 20px 0;
}

.contactButton:hover {
  transform: translateY(-2px);
}

.contactButton:active {
  transform: translateY()
}

/* Loading */
#loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.loader {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}

.jimu-primary-loading:before,
.jimu-primary-loading:after {
  position: absolute;
  top: 0;
  content: '';
}

.jimu-primary-loading:before {
  left: -19.992px;
}

.jimu-primary-loading:after {
  left: 19.992px;
  -webkit-animation-delay: 0.32s !important;
  animation-delay: 0.32s !important;
}

.jimu-primary-loading:before,
.jimu-primary-loading:after,
.jimu-primary-loading {
  background: #076fe5;
  -webkit-animation: loading-keys-app-loading 0.8s infinite ease-in-out;
  animation: loading-keys-app-loading 0.8s infinite ease-in-out;
  width: 13.6px;
  height: 32px;
}

.jimu-primary-loading {
  text-indent: -9999em;
  margin: auto;
  position: absolute;
  right: calc(50% - 6.8px);
  top: calc(50% - 16px);
  -webkit-animation-delay: 0.16s !important;
  animation-delay: 0.16s !important;
}

@-webkit-keyframes loading-keys-app-loading {
  0%,
  80%,
  100% {
    opacity: .75;
    box-shadow: 0 0 #076fe5;
    height: 32px;
  }

  40% {
    opacity: 1;
    box-shadow: 0 -8px #076fe5;
    height: 40px;
  }
}

@keyframes loading-keys-app-loading {
  0%,
  80%,
  100% {
    opacity: .75;
    box-shadow: 0 0 #076fe5;
    height: 32px;
  }

  40% {
    opacity: 1;
    box-shadow: 0 -8px #076fe5;
    height: 40px;
  }
}

/* Valorations */
.valorations {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 20px;
  border-radius: 10px;
  background-color: white;
  box-shadow: #0E2945 0 4px 8px;
  color: #0E2945;
  margin: 20px 0;
  width: 75%;
}

.valorations h2 {
  font-weight: 700;
  color: #0E2945;
  margin-bottom: 10px;
}

.valorations-comments {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.combinedData {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 20px;
  font-weight: 400;
  font-size: 16px;
  color: #0E2945;
  padding: 10px 0;
  border: 1px solid #0E2945;
  border-radius: 10px;
  margin: 10px 0;
  box-shadow: #0e294567 0 1px 8px;
  padding: 1em;
}

.combinedData p {
  margin: 0;
}

.combinedData img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.flex {
  display: flex;
  gap: 10px;
}

.flex-start {
  justify-content: flex-start;
}

.column {
  flex-direction: column;
}

.border-bottom-light {
  border-bottom: 1px solid #ccc;
}


</style>
