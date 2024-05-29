<template>
  <div class="review-list">
    <ul>
      <li v-for="review in reviews" :key="review.id" class="review-item">
        <div class="review-content">
          <p class="review-comment">{{ review.comment }}</p>
          <p class="review-rate">Valoración: {{ review.rate }}</p>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      reviews: []
    };
  },
  mounted() {
    this.fetchReviews();
  },
  methods: {
    async fetchReviews() {
      try {
        const response = await fetch('http://18.212.255.200:8080/index.php?path=valorations');
        const data = await response.json();
        this.reviews = data;
      } catch (error) {
        console.error('Error fetching reviews:', error);
      }
    }
  }
};
</script>

<style scoped>
.review-list {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.review-list ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.review-item {
  background-color: #ffffff;
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.review-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.review-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.review-comment {
  font-size: 1em;
  color: #333;
  margin: 0;
}

.review-rate {
  font-size: 1em;
  color: #0E2945;
  margin: 0;
  font-weight: bold;
}
</style>
