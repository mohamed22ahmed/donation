<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from 'axios';
import Offer from '@/Components/Offer.vue';
import Rating from "@/Components/Rating.vue";
import StarRatingDisplay from "@/Pages/Ratings/strRatingDisplay.vue";
import "@fortawesome/fontawesome-free/css/all.css";
import showOrderModal from "@/Pages/Orders/showOrderModal.vue";


export default {
  components: {
    showOrderModal,
    StarRatingDisplay,
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
      isOrderModalOpen: false,
      selectedOrder: {},
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
    },

    showOrder(order_id) {
      this.getOrder(order_id).then(() => {
        this.isOrderModalOpen = true
      })
    },

    async getOrder(order_id) {
      const response = await axios.get(route('orders.show', order_id))

      this.selectedOrder = response.data
    },

    closeModal() {
      this.isOrderModalOpen = false;
      this.selectedOrder = {};
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
          <div class="px-5 font-semibold" style="font-size: 30px">Offers</div>

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

      <div class="pt-2 px-5">
        <div class="font-semibold" style="font-size: 30px">Ratings</div>

          <table>
            <thead>
            <tr>
              <th>#</th>
              <th>Order</th>
              <th>User</th>
              <th>Rating</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="rating in ratings.data" :key="rating.id">
              <td>{{ rating.id }}</td>
              <td>
                <button
                    type="button"
                    class="pl-3 text-green-500 text-lg hover:text-gray-500"
                    @click="showOrder(rating.order_id)"
                >
                  <i class="fa-solid fa-eye"></i>
                </button>
              </td>
              <td>
                {{ rating.user.name }}
              </td>
              <td>
                <StarRatingDisplay :rating="rating.degree || 0" />
                <span class="text-sm text-gray-500 ml-1">({{ rating.degree || 0 }})</span>
              </td>
            </tr>
            </tbody>
          </table>
      </div>
      <div class="d-flex justify-content-center pagination-container pb-5 pt-4">
        <button
            v-if="ratings.total > 0"
            v-for="page in ratings.last_page"
            :key="page"
            @click="getRatings(page)"
            class="paginate-buttons"
            :class="{ active: page === currentRatingPage, 'active-page': page === currentRatingPage }"
        >
          {{ page }}
        </button>
      </div>

    </AuthenticatedLayout>

  <showOrderModal
      v-if="isOrderModalOpen"
      :order="selectedOrder"
      @close="closeModal"
  />
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

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

th {
  background-color: #4a5568;
  color: white;
}
</style>