<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from 'axios';
import Offer from '@/Components/Offer.vue';
import Rating from "@/Components/Rating.vue";

export default {
  components: {
    Rating,
    Offer,
    AuthenticatedLayout,
    Head
  },

  mounted() {
    this.getOffers()
    this.getRatings()
  },

  data() {
    return {
      offers: {},
      ratings: {},
      currentPage: 1,
      currentRatingPage: 1,
    };
  },

  methods: {
    getOffers(page = 1){
      this.currentPage = page;
      axios.get('/dashboard/getOffers?page=' + page)
          .then((response) => {
            this.offers = response.data;
          });
    },

    getRatings(page = 1){
      this.currentRatingPage = page;
      axios.get('/dashboard/getRatings?page=' + page)
          .then((response) => {
            this.ratings = response.data;
          });
    },

    handleOfferUpdate() {
      this.getOffers()
      this.getRatings()
    }
  }
};
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Home
            </h2>
        </template>

        <div class="pt-12 row d-flex flex-wrap">
          <div class="col-md-4 p-5 d-flex" v-for="offer in offers.data" :key="offer.id">
            <Offer :offer="offer" class="flex-grow-1" @offer-updated="handleOfferUpdate"/>
          </div>
        </div>
        <div class="d-flex justify-content-center pagination-container pb-5">
          <button
              v-if="offers.total > 0"
              v-for="page in offers.last_page"
              :key="page"
              @click="getOffers(page)"
              class="paginate-buttons"
              :class="{ active: page === currentPage, 'active-page': page === currentPage }"
          >
            {{ page }}
          </button>
        </div>


    </AuthenticatedLayout>
</template>

<style>
.pagination-container {
  display: flex;
  column-gap: 10px;
}
.paginate-buttons {
  height: 40px;
  width: 40px;
  border-radius: 20px;
  cursor: pointer;
  background-color: rgb(242, 242, 242);
  border: 1px solid rgb(217, 217, 217);
  color: black;
}
.paginate-buttons:hover {
  background-color: #d8d8d8;
}
.active-page {
  background-color: #3498db;
  border: 1px solid #3498db;
  color: white;
}
.active-page:hover {
  background-color: #2988c8;
}
</style>