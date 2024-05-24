<template>
    <div class="product-form-container">
        <form @submit.prevent="addProduct" class="product-form">
            <h1>Agregar Producto</h1>
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" v-model="productName" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" v-model="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Precio</label>
                <input type="number" id="price" v-model="price" required>
            </div>
            <div class="form-group">
                <label for="location">Ubicación</label>
                <input type="text" id="location" v-model="location" required>
            </div>
            <div class="form-group">
                <label for="category">Categoría</label>
                <select id="category" v-model="category" required>
                    <option value="Electronics">Electrónica</option>
                    <option value="Clothing">Ropa</option>
                    <option value="Home">Hogar</option>
                    <option value="Sports">Deportes</option>
                    <option value="Books">Libros</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">URL de la Imagen</label>
                <input type="text" id="image" v-model="images" required>
            </div>
            <button type="submit" class="submit-button">Agregar Producto</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            productName: '',
            description: '',
            price: null,
            location: JSON.parse(localStorage.getItem('user')).location,
            category: null,
            images: ''
        };
    },
    created() {
        console.log('Location:', this.location);
    },
    methods: {
        async addProduct() {
            const ownerId = JSON.parse(localStorage.getItem('user')).id;
            console.log('Product:', this.description, this.location, this.price, this.images, ownerId, this.productName, this.category);
            try {
                const response = await fetch('http://54.226.222.35:8080/index.php?path=product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        description: this.description,
                        location: this.location,
                        price: this.price,
                        images: this.images,
                        ownerId: ownerId,
                        productName: this.productName,
                        category: this.category
                    })
                });
                const data = await response.json();
                console.log('Product added:', data);
                if (data.status === 'Product added') {
                    this.$router.push('/');
                } else {
                    alert('Error al agregar el producto');
                }
            } catch (error) {
                console.error('Error adding product:', error);
            }
        }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap');

body {
    font-family: 'Merriweather', serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.product-form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f9f9f9;
    padding: 20px;
    
}

.product-form {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    padding-right: 55px;
}
#category {
    padding-right: 511px; 
}

.product-form h1 {
    font-weight: 700;
    color: #0E2945;
    margin-bottom: 20px;
    text-align: center;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 700;
    color: #0E2945;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.form-group textarea {
    resize: vertical;
}

.submit-button {
    width: 100%;
    padding: 10px;
    background-color: #0E2945;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-button:hover {
    background-color: #092134;
}
</style>
