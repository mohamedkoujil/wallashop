<template>
    <div>
      <ul class="product-list">
        <li v-for="product in products" :key="product.id">
          <div class="product-item">
            <div class="product-info">
              <strong>{{ product.productname }}</strong>
              <p>Precio: {{ product.price }}</p>
            </div>
            <div class="product-actions">
              <button @click="viewProduct(product.id)">Ver Detalles</button>
              <button @click="deleteProduct(product.id)">Borrar</button>
            </div>
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
        fetch('http://3.87.167.210:8080/index.php?path=products')
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
          fetch(`http://3.87.167.210:8080/index.php?path=product&id=${productId}`, {
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
    margin: 0;
  }
  
  .product-list li {
    margin-bottom: 10px;
  }
  
  .product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  }
  
  .product-info {
    flex-grow: 1;
  }
  
  .product-actions {
    display: flex;
    gap: 10px;
  }
  
  button {
    background-color: #0E2945;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  button:hover {
    background-color: #08223b;
  }
  </style>
  