<template>
  <div class="profile-page">
    <ProfileCard :profile="profile" />
  </div>
</template>

<script>
import ProfileCard from '@/components/ProfileCard.vue';

export default {
  name: 'ProfilePage',
  components: {
    ProfileCard
  },
  data() {
    return {
      profile: {
        email: '',
        location: '',
        profilePicture: '',
        nivell: '',
        rate: 0
      },
      apiUrl: 'http://100.27.221.69:8080/index.php?path=profile'
    };
  },
  methods: {
    fetchProfile() {
      fetch(this.apiUrl)
        .then(response => response.json())
        .then(data => {
          this.profile = data;
        })
        .catch(error => {
          console.error('Error fetching profile:', error);
        });
    },
  },
  mounted() {
    this.fetchProfile();
  },
};
</script>

<style scoped>
.profile-page {
  padding: 20px;
  max-width: 800px;
  margin: auto;
  background-color: #f4f4f4;
}
</style>
