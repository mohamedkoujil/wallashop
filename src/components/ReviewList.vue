<template>
  <div class="review-list">
    <ul>
      <li v-for="review in reviews" :key="review.id">
        {{ review.comment }} - {{ review.rate }}
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
  text-align: left;
}

.review-list ul {
  list-style: none;
  padding: 0;
}

.review-list li {
  padding: 5px 0;
}
</style>
