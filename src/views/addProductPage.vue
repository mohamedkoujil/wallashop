<template>
    <form @submit.prevent="addProduct">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" v-model="productName" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" v-model="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" v-model="price" required>
        </div>
        <div class="form-group">
            <label for="location">{{location}}</label>
            <input type="text" id="location" v-model="location" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" v-model="category" required>
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
                <option value="Home">Home</option>
                <option value="Sports">Sports</option>
                <option value="Books">Books</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image URL</label>
            <input type="text" id="image" v-model="images" required>
        </div>
        <button type="submit">Add product</button>
    </form>
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
                    alert('Error adding product');
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

.product-detail {
    padding: 20px;
    text-align: center;
}

h1,
h2 {
    font-weight: 700;
    color: #0E2945;
}

.product-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
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

.product-category,
.product-description,
.product-price,
.product-location,
.product-owner {
    font-weight: 400;
    font-size: 16px;
}

.product-category strong,
.product-description strong,
.product-price strong,
.product-location strong,
.product-owner strong {
    color: #0E2945;
}

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
</style>
