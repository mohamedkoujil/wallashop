<template>
    <div>
      <ul class="product-list">
        <li v-for="product in products" :key="product.id">
          <div>
            <strong>{{ product.productname }}</strong>
            <p>Precio: {{ product.price }}</p>
            <button @click="viewProduct(product.id)">Ver Detalles</button>
            <button @click="deleteProduct(product.id)">Borrar</button>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ProductList',
    data() {
      return {
        products: []
      };
    },
    methods: {
      fetchProducts() {
        fetch('http://18.212.255.200:8080/index.php?path=products')
          .then(response => response.json())
          .then(data => {
            this.products = data;
          })
          .catch(error => {
            console.error('Error fetching products:', error);
          });
      },
      viewProduct(productId) {
        this.$router.push({ name: 'product-detail', params: { id: productId } });
      },
      deleteProduct(productId) {
        if (confirm('¿Estás seguro de que deseas borrar este producto?')) {
          fetch(`http://18.212.255.200:8080/index.php?path=product&id=${productId}`, {
            method: 'DELETE'
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'Product deleted') {
              alert('Producto borrado con éxito.');
              this.fetchProducts(); // Refresh the list after deletion
            } else {
              alert('Error al borrar el producto.');
            }
          })
          .catch(error => {
            console.error('Error deleting product:', error);
          });
        }
      }
    },
    mounted() {
      this.fetchProducts();
    }
  };
  </script>
  
  <style scoped>
  .product-list {
    list-style: none;
    padding: 0;
  }
  
  .product-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
  }
  
  button {
    background-color: #0E2945;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 5px;
  }
  
  button:hover {
    background-color: #8292A4;
  }
  </style>
  